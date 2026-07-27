<?php

namespace Aic\Faq\Models;

use Aic\Faq\Models\Categories;
use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\App;
use Winter\Storm\Database\Model;
use Winter\Storm\Support\Facades\DB;

class Faqs extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aic_faq_faqs';
    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

    /**
     * Validation rules
     */
    public $rules = [
        'question'     => 'required',
        'answer'       => 'required',
        'is_published' => 'required|between:0,2|numeric',
        'is_featured'  => 'required|between:0,1|numeric'
    ];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'question',
        'answer'
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /*
     * Relations
     */
    public $belongsTo = [
        'category' => ['Aic\Faq\Models\Categories']
    ];

    /**
     * Allowed sorting options
     *
     * @var array
     */
    public static $allowedSorting = [
        'category_id asc',
        'category_id desc',
        'created_at asc',
        'created_at desc'
    ];

    //
    // Scopes
    //

    /**
     * Scope a query to only include published FAQs.
     * 
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  array                     $categories List of category ids
     * 
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeIsPublished($query)
    {
        // if backend user is logged in
        if (BackendAuth::check()) {
            // return published and draft posts
            return $query->whereIn('is_published', [1, 2]);
        } else {
            // return published posts
            return $query->where('is_published', 1);
        }

    }

    /**
     * Scope a query to only include featured or not featured FAQs.
     * 
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  int                       $isFeatured Featured status (0 = not featured, 1 = featured, 2 = all)
     * 
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeIsFeatured($query, $isFeatured)
    {
        // return all featured_statusses
        if ($isFeatured == 2) return;

        // return the featured_status (featured or not_featured)
        return $query->where('is_featured', $isFeatured);
    }

    /**
     * Scope a query to only include FAQs of a specific category.
     * 
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  int                       $categoryId Category id
     * 
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeCategory($query, $categoryId)
    {
        // return all categories
        if ($categoryId == 0) return;

        // return the category
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to only include FAQs that are translated.
     * 
     * @param  Illuminate\Query\Builder  $query        QueryBuilder
     * @param  bool                      $isTranslated Translated status (true = translated, false = all)
     *  @return Illuminate\Query\Builder                QueryBuilder
     */
    public function scopeTranslatedOnly($query, $isTranslated)
    {
        // cancel if translated is not checked
        if (!$isTranslated) return;

        // cancel if Winter.Translate isn't installed
        if (!class_exists('Winter\Translate\Behaviors\TranslatableModel')) return;

        // get current and default locale
        $currentLocale = App::getLocale();
        $defaultLocale = DB::table('winter_translate_locales')->where('is_default', 1)->value('code');

        // get which FAQs can be shown
        if ($currentLocale != $defaultLocale) {
            $ids = DB::table('winter_translate_attributes')
                        ->where('model_type', 'Aic\Faq\Models\Faqs')
                        ->where('locale', $currentLocale)
                        ->where('attribute_data', 'not like', '%"question":""%')
                        ->pluck('model_id');
            return $query->whereIn('id', $ids);
        }
    }

    /**
     * Scope a query to only include FAQs that match the search query.
     * 
     * @param  Illuminate\Query\Builder  $query          QueryBuilder
     * @param  string                    $searchQuery    Search query
     * @param  array                     $searchableFields List of searchable fields
     * 
     * @return Illuminate\Query\Builder                  QueryBuilder
     */
    public function scopeSearchQuery($query, $searchQuery, $searchableFields)
    {
        // cancel is searchQuery is empty
        if ($searchQuery == '') return;

        // if Winter.Translate is installed
        if (class_exists('Winter\Translate\Behaviors\TranslatableModel')) {

            // get current and default locale
            $currentLocale = App::getLocale();
            $defaultLocale = DB::table('winter_translate_locales')->where('is_default', 1)->value('code');

            // search on the FAQs in the correct language
            if ($currentLocale != $defaultLocale) {

                $ids = DB::table('winter_translate_attributes')
                            ->where('model_type', 'Aic\Faq\Models\Faqs')
                            ->where('locale', $currentLocale)
                            ->where('attribute_data', 'like', '%' . $searchQuery . '%')
                            ->pluck('model_id');
                return $query->whereIn('id', $ids);

            }

        }

        // return the search if Winter.Translate is NOT installed
        // and if $currentLocale == $defaultLocale
        return $query->searchWhere($searchQuery, $searchableFields);
    }

    /**
     * Scope a query to sort FAQs based on the allowed sorting options.
     *
     * @param  Illuminate\Query\Builder  $query QueryBuilder
     * @param  string                    $sort  Sorting option
     *
     * @return Illuminate\Query\Builder        QueryBuilder
     */
    public function scopeSortFAQs($query, $sort)
    {
        foreach (self::$allowedSorting as $sorter) {
            // check if sorter is equal to sort
            if ($sorter != $sort) continue;

            // split sort method
            $sort = explode(' ', $sort);

            // sort the query
            $query->orderBy($sort[0], $sort[1]);
        }
    }

    /**
     * Scope a query to get FAQs based on the options provided by the FAQ component.
     *
     * @param  Illuminate\Query\Builder  $query   QueryBuilder
     * @param  array                     $options Options provided by the FAQ component
     *
     * @return Illuminate\Query\Builder          QueryBuilder
     */
    public function scopeListFrontEnd($query, $options)
    {
        // merge settings with component default properties
        extract(array_merge([
            'categoryId' => 0,
            'isFeatured' => 2,
            'isSearch' => 1,
            'isTranslated' => 1,
            'isPublished' => 1,
            'searchQuery' => ''
        ], $options));

        // define search fields
        $searchFields = [
            'question',
            'answer'
        ];

        // set query
        $query->isPublished();
        $query->isFeatured($isFeatured);
        $query->category($categoryId);
        $query->translatedOnly($isTranslated);
        $query->searchQuery($searchQuery, $searchFields);
        $query->sortFAQs($sort);

        // get FAQs based on the query
        return $query->get();
    }
}
