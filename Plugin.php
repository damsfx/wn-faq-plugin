<?php

namespace Aic\Faq;

use Backend\Facades\Backend;
use System\Classes\PluginBase;

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
                    ]
                ]
            ]
        ];
    }

    /**
     * Register the permissions provided by this plugin
     */
    public function registerPermissions(): array
    {
        return [
            'aic.faq.*' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permission.faq'
            ]
        ];
    }

    /**
     * Register the CMS components provided by this plugin
     */
    public function registerComponents(): array
    {
        return [
            'Aic\Faq\Components\Faqs' => 'FAQ'
        ];
    }
}