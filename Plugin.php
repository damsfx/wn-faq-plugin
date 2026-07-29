<?php

namespace Aic\Faq;

use Backend\Facades\Backend;
use Winter\User\Models\UserRole;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'aic.faq::lang.plugin.name',
            'description' => 'aic.faq::lang.plugin.description',
            'author'      => 'Meindert Stijfhals',
            'icon'        => 'icon-question-circle'
        ];
    }

    /**
     * Register the CMS components provided by this plugin
     */
    public function registerComponents(): array
    {
        return [
            'Aic\Faq\Components\Categories' => 'faqCategories',
            'Aic\Faq\Components\Faqs' => 'FAQ',
        ];
    }

    /**
     * Register the permissions provided by this plugin
     */
    public function registerPermissions(): array
    {
        return [
            'access_settings' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permissions.access_settings',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
            'aic.faq.manage_categories' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permissions.manage_categories',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
            'aic.faq.manage_faqs' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permissions.manage_faqs',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    /**
     * Register the backend navigation items provided by this plugin
     */
    public function registerNavigation(): array
    {
        return [
            'faq' => [
                'label'       => 'aic.faq::lang.menu.faqs',
                'url'         => Backend::url('aic/faq/faqs'),
                'icon'        => 'icon-question-circle',
                'permissions' => ['aic.faq.*'],
                'order'       => 900,

                'sideMenu' => [
                    'faqs' => [
                        'label'       => 'aic.faq::lang.menu.faqs',
                        'url'         => Backend::url('aic/faq/faqs'),
                        'icon'        => 'icon-question-circle',
                        'order'       => 100
                    ],
                    'categories' => [
                        'label'       => 'aic.faq::lang.menu.categories',
                        'url'         => Backend::url('aic/faq/categories'),
                        'icon'        => 'icon-folder-open-o',
                        'order'       => 200
                    ],
                    'settings' => [
                        'label'       => 'aic.faq::lang.menu.settings',
                        'url'         => Backend::url('system/settings/update/aic/faq/settings'),
                        'class'       => 'Aic\Faq\Models\Settings',
                        'icon'        => 'icon-cogs',
                        'order'       => 300
                    ],
                ]
            ]
        ];
    }

    /**
     * Registers the settings provided by this plugin.
     */
    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label'       => 'aic.faq::lang.menu.settings',
                'description' => 'aic.faq::lang.menu.settings_description',
                'icon'        => 'icon-question-circle',
                'class'       => 'Aic\Faq\Models\Settings',
                'category'    => SettingsManager::CATEGORY_CMS,
                'order'       => 1500,
                'keywords'    => 'faq questions answers',
                'permissions' => ['aic.faq.access_settings']
            ]
        ];
    }

    /**
     * Register custom column types for the backend list view
     */
    public function registerListColumnTypes(): array
    {
        return [
            'publishedstatus' => function ($value) {
                $class = [
                    0 => 'text-danger',
                    1 => 'text-success',
                    2 => 'text-warning'
                ];

                return '<span class="wn-icon-circle '. $class[$value] .'">'.
                    \Aic\Faq\Classes\Enums\PublishStatusEnum::nameTranslated($value)
                    .'</span>';
            },
            'featuredstatus' => function ($value) {
                $class = [
                    0 => '',
                    1 => 'text-success'
                ];

                return '<span class="wn-icon-circle '. $class[$value] .'">'.
                    \Aic\Faq\Classes\Enums\FeaturedStatusEnum::nameTranslated($value)
                    .'</span>';
            }
        ];
    }
}
