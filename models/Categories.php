<?php

namespace Aic\Faq\Models;

use Winter\Storm\Database\Model;

class Categories extends Model
{
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

    //
    // Getters
    //

    /**
     * Get the list of categories for dropdowns
     */
    public function getCategoryOptions(): array
    {
        return Categories::orderBy('name', 'asc')->lists('name', 'id');
    }
}
