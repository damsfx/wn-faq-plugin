<?php

namespace Aic\Faq\Models;

use Winter\Storm\Database\Model;

class Categories extends Model
{
    use \Aic\Faq\Classes\Traits\HasPublishStatus;
    use \Aic\Faq\Classes\Traits\Urlable;
    use \Winter\Storm\Database\Traits\Validation;

    protected $table = 'aic_faq_categories';
    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required'
    ];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'name'
    ];

    /*
     * Relations
     */
    public $hasMany = [
        'faqs' => [
            Faqs::class,
            'key' => 'category_id',
            'order' => 'published_at desc',
            'scope' => 'isPublished',
        ],
    ];

    //
    // Getters
    //

    /**
     * Get the list of categories for dropdowns
     */
    public function getCategoriesListOptions(): array
    {
        return Categories::orderBy('name', 'asc')->lists('name', 'id');
    }
}
