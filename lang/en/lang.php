<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Questions and answers. Assign them to a category, add featured statusses and manage which ones are displayed on the frontend.'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categories',
        'settings' => 'FAQs',
        'settings_description' => 'Manage the settings of the FAQ plugin',
    ],

    'controllers' => [
        'buttons' => [
            'return' => 'Return'
        ],
        'faqs' => [
            'new' => 'New FAQ'
        ],
        'categories' => [
            'new' => 'New category'
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'FAQ Categories',
            'description' => 'List of FAQ categories',
            'all_label' => 'All questions',
            'properties' => [
                'links' => 'Links',
                'slug' => [
                    'title' => 'Category slug',
                    'description' => 'Slug of the active category, used to highlight it in the list'
                ],
                'faq_page' => [
                    'title' => 'Overview page',
                    'description' => 'Page that shows all FAQs'
                ],
                'category_page' => [
                    'title' => 'Category page',
                    'description' => 'Page that shows the FAQs of one category'
                ]
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'List of FAQs',
            'search_button' => 'Search',
            'search_placeholder' => 'What are you looking for?',
            'no_results' => 'No FAQ found.',
            'properties' => [
                'search_group' => 'Search',
                'category' => [
                    'title' => 'Category',
                    'description' => 'Choose which category to display',
                    'all' => 'All categories',
                    'no_category_label' => 'Other',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Choose which FAQs to display',
                    'options' => [
                        0 => 'All except featured',
                        1 => 'Featured only',
                        2 => 'All FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Search minimum results',
                    'description' => 'Minimum amount of results for the search field to show. Must be a number',
                    'validationMessage' => 'Must be a number',
                ],
                'search' => [
                    'title' => 'Search enabled',
                    'description' => 'Enable the search functionality',
                ],
                'sort' => [
                    'title'       => 'Sort',
                    'description' => 'Choose the display order of the FAQs',
                    'options'     => [
                        'category_id_asc'  => 'Category ascending',
                        'category_id_desc' => 'Category descending',
                        'created_at_asc'   => 'Created at ascending',
                        'created_at_desc'  => 'Created at descending',
                    ]
                ],
                'translated' => [
                    'title' => 'Translated FAQs only',
                    'description' => 'Show only the translated FAQs in the current language',
                ],
            ],
        ]
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Name',
            'none_options' => '-- None --',
            'published_status' => 'Published',
            'slug' => 'Slug',
            'total' => 'Total',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
            'featured_status' => 'Featured',
        ],
        'category' => [
            'label' => 'Category',
            'label_plural' => 'Categories',
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Question',
            'answer' => 'Answer',
        ],
        'settings' => [
            'title' => 'Title',
            'intro' => 'Intro',
            'meta_title' => 'Meta title',
            'meta_description' => 'Meta description',
            'og_title' => 'Open Graph title',
            'og_description' => 'Open Graph description',
            'og_image' => 'Open Graph image',
            'sections' => [
                'meta' => 'Meta tags',
                'meta_comment' => 'Meta tags used for SEO purposes. Used as default if not set on a specific FAQ or category.',
                'open_graph' => 'Open Graph',
                'open_graph_comment' => 'Open Graph tags used for social media sharing. Used as default if not set on a specific FAQ or category.',
            ],
            'tabs' => [
                'general' => 'General',
                'meta' => 'Meta',
                'open_graph' => 'Open Graph',
            ],
        ],
    ],

    'permissions' => [
        'access_settings' => 'Access FAQ settings',
        'manage_categories' => 'Manage FAQ Categories',
        'manage_faqs' => 'Manage FAQs',
    ],

    'enums' => [
        'featured_status' => [
            '0' => 'Not featured',
            '1' => 'Featured',
        ],
        'publish_status' => [
            0 => 'Not published',
            1 => 'Published',
            2 => 'In draft'
        ],
    ]
];
