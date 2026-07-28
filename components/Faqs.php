<?php

namespace Aic\Faq\Components;

use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Lang;
use Winter\Storm\Database\Collection;

class Faqs extends ComponentBase
{
    /**
     * A collection of faqs to display
     */
    public Collection|null $faqs;

    /**
     * Array of faq grouped by category
     */
    public array $faqsPerCategory;

    /**
     * Whether to show the search form
     */
    public bool $isSearch;

    /**
     * Search label
     */
    public string $searchLabel;

    /**
     * Search placeholder
     */
    public string $searchPlaceholder;

    /**
     * Minimum number of FAQs to show the search form
     */
    public int $minSearchResults;

    /**
     * The search query
     */
    public string $searchQuery;

    /**
     * Gets the details for the component
     */
    public function componentDetails()
    {
        return [
            'name'        => 'aic.faq::lang.components.faqs.title',
            'description' => 'aic.faq::lang.components.faqs.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [
            'sort' => [
                'title'       => 'aic.faq::lang.component.settings.sort.title',
                'description' => 'aic.faq::lang.component.settings.sort.description',
                'type'        => 'dropdown',
                'default'     => 'category_id asc',
                'options'     => [
                    'category_id asc'  => 'aic.faq::lang.component.settings.sort.options.category_id_asc',
                    'category_id desc' => 'aic.faq::lang.component.settings.sort.options.category_id_desc',
                    'created_at asc'   => 'aic.faq::lang.component.settings.sort.options.created_at_asc',
                    'created_at desc'  => 'aic.faq::lang.component.settings.sort.options.created_at_desc'
                ]
            ],
            'categoryId' => [
                'title'       => 'aic.faq::lang.component.settings.category.title',
                'description' => 'aic.faq::lang.component.settings.category.description',
                'type'        => 'dropdown',
                'default'     => 0
            ],
            'isFeatured' => [
                'title'       => 'aic.faq::lang.component.settings.featured.title',
                'description' => 'aic.faq::lang.component.settings.featured.description',
                'type'        => 'dropdown',
                'default'     => 2,
                'options'     => [
                    0 => 'aic.faq::lang.component.settings.featured.not_featured',
                    1 => 'aic.faq::lang.component.settings.featured.featured',
                    2 => 'aic.faq::lang.component.settings.featured.all'
                ]
            ],
            'isSearch' => [
                'title'       => 'aic.faq::lang.component.settings.search.title',
                'description' => 'aic.faq::lang.component.settings.search.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
            'minSearchResults' => [
                'title'       => 'aic.faq::lang.component.settings.minSearchResults.title',
                'description' => 'aic.faq::lang.component.settings.minSearchResults.description',
                'default'     => 10,
                'type'        => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'aic.faq::lang.component.settings.minSearchResults.validationMessage'
            ],
            'isTranslated' => [
                'title'       => 'aic.faq::lang.component.settings.translated.title',
                'description' => 'aic.faq::lang.component.settings.translated.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $this->prepareVars();

        $this->faqs = $this->getFAQs();
        $this->faqsPerCategory = $this->faqsPerCategory($this->faqs);
        $this->minSearchResults = $this->showSearch();
    }

    /**
     * Prepare variables for the component
     */
    protected function prepareVars()
    {
        $this->isSearch = (bool) $this->property('isSearch');
        $this->searchLabel = Lang::get('aic.faq::lang.component.settings.search.button_label');
        $this->searchPlaceholder = Lang::get('aic.faq::lang.component.settings.search.input_placeholder');
        $this->searchQuery = (string) trim(input('q'));
    }


    protected function showSearch(): bool
    {
        $minResults = (int) $this->property('minSearchResults');

        $faqsWithoutSearch = Faq::listFrontEnd([
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (bool) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated')
        ])->count();

        if ($faqsWithoutSearch >= $minResults) {
            return true;
        }
        return false;
    }

    /**
     * Get the FAQs based on the component properties
     */
    protected function getFAQs(): Collection
    {
        $faqs = Faq::listFrontEnd([
            'sort'         => $this->property('sort'),
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (bool) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated'),
            'searchQuery'  => $this->searchQuery
        ]);

        return $faqs;
    }

    protected function faqsPerCategory(Collection $faqs): array
    {
        // get properties
        $categoryId = (int) $this->property('categoryId');
        $sort = $this->property('sort');

        // get category name
        $categoryName = $this->getCategoryName($categoryId);

        // if category name is not 0 (all)
        if ($categoryName != 0) {
            // return the FAQs with their category
            return [
                [
                    'name' => $categoryName,
                    'faqs' => $faqs
                ]
            ];
        } else {
            // create new array
            $newArray = [];

            // prepare the array with the categories
            $categories = [];
            if ($sort == 'category_id asc') {
                $categories = Categories::orderBy('id', 'asc')->get();
            } elseif ($sort == 'category_id desc') {
                $categories = Categories::orderBy('id', 'desc')->get();
            } else {
                $categories = Categories::get();
            }

            foreach ($categories as $category) {
                $newArray[$category->id] = [
                    'categoryName' => $category->name,
                    'faqs' => []
                ];
            }

            // push the faq to the right category
            foreach ($faqs as $faq) {
                $newArray[$faq->category_id]['faqs'][] = $faq;

                // if categoryName doesn't exist
                // set no_category_label
                if (!array_key_exists('categoryName', $newArray[$faq->category_id])) {
                    $newArray[$faq->category_id]['categoryName'] = Lang::get('aic.faq::lang.component.settings.category.no_category_label');
                }
            }

            // remove empty categories
            foreach ($newArray as $key => $value) {
                $amountFaqs = count($value['faqs']);
                if ($amountFaqs == 0) {
                    unset($newArray[$key]);
                };
            }

            // return the new array
            return $newArray;
        }
    }

    protected function getCategoryName(int $categoryId): string|int
    {
        if ($categoryId == 0) {
            return 0;
        }

        return Categories::find($categoryId)->name;
    }

    public function getCategoryIdOptions()
    {
        // return all categories for the dropdown
        $categories = Categories::lists('name', 'id');
        $categories[0] = 'aic.faq::lang.component.settings.category.all';
        ksort($categories);

        return $categories;
    }
}
