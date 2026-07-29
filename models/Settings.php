<?php

namespace Aic\Faq\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /**
     * @var array Behaviors implemented by this model.
     */
    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel',
        \System\Behaviors\SettingsModel::class
    ];

    /**
     * @var string Unique code
     */
    public $settingsCode = 'aic_faq_settings';

    /**
     * @var mixed Settings form field definitions
     */
    public $settingsFields = 'fields.yaml';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'title',
        'intro',
    ];
}
