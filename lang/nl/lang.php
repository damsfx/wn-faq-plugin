<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Frequently Asked Questions. Vragen en antwoorden. Geef ze een categorie, voeg uitgelichte statussen toe en beheer welke je wil weergeven op de frontend.'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Categorieën',
        'settings' => 'FAQs',
        'settings_description' => 'Beheer de instellingen van de FAQ-plugin',
    ],

    'controllers' => [
        'buttons' => [
            'return' => 'Vorige'
        ],
        'faqs' => [
            'new' => 'Nieuwe FAQ'
        ],
        'categories' => [
            'new' => 'Nieuwe categorie'
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'FAQ-categorieën',
            'description' => 'Lijst van FAQ-categorieën',
            'all_label' => 'Alle vragen',
            'properties' => [
                'links' => 'Links',
                'category_page' => [
                    'title' => 'Categoriepagina',
                    'description' => 'Pagina die de FAQs van één categorie toont'
                ],
                'faq_page' => [
                    'title' => 'Overzichtspagina',
                    'description' => 'Pagina die alle FAQs toont'
                ],
                'slug' => [
                    'title' => 'Categorie-slug',
                    'description' => 'Slug van de actieve categorie, gebruikt om die te markeren in de lijst'
                ],
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'Lijst van FAQs',
            'search_button' => 'Zoeken',
            'search_placeholder' => 'Naar wat ben je op zoek?',
            'no_results' => 'Geen FAQ gevonden.',
            'properties' => [
                'search_group' => 'Zoeken',
                'category' => [
                    'title' => 'Categorie',
                    'description' => 'Kies de categorie om weer te geven',
                    'all' => 'Alle categorieën',
                    'no_category_label' => 'Andere',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Kies de FAQs om weer te geven',
                    'options' => [
                        0 => 'Alleen niet-uitgelichte',
                        1 => 'Alleen uitgelichte',
                        2 => 'Alle FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Minimum zoek resultaten',
                    'description' => 'De minimum hoeveelheid zoek resultaten om het zoek veld weer te geven. Moet een nummer zijn.',
                    'validationMessage' => 'Moet een nummer zijn',
                ],
                'search' => [
                    'title' => 'Zoeken inschakelen',
                    'description' => 'Scakel de zoek functionaliteit in',
                ],
                'sort' => [
                    'title'       => 'Sorteer',
                    'description' => 'Kies de weergave volgorde van de FAQs',
                    'options'     => [
                        'category_id_asc'  => 'Categorie oplopend',
                        'category_id_desc' => 'Categorie aflopend',
                        'created_at_asc'   => 'Aangemaakt op oplopend',
                        'created_at_desc'  => 'Aangemaakt op aflopend',
                    ]
                ],
                'translated' => [
                    'title' => 'Enkel vertaalde FAQs',
                    'description' => 'Toon enkel de FAQs vertaald in de huidige taal',
                ],
            ],
        ]
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Naam',
            'none_options' => '-- Geen --',
            'published_status' => 'Gepubliceerd',
            'slug' => 'Slug',
            'total' => 'Totaal',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Aangepast op',
            'featured_status' => 'Uitgelicht',
        ],
        'category' => [
            'label' => 'Categorie',
            'label_plural' => 'Categorieën',
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Vraag',
            'answer' => 'Antwoord',
        ],
        'settings' => [
            'title' => 'Titel',
            'intro' => 'Intro',
            'meta_title' => 'Meta titel',
            'meta_description' => 'Meta beschrijving',
            'og_title' => 'Open Graph titel',
            'og_description' => 'Open Graph beschrijving',
            'og_image' => 'Open Graph afbeelding',
            'sections' => [
                'meta' => 'Meta tags',
                'meta_comment' => 'Meta tags gebruikt voor SEO-doeleinden. Wordt standaard gebruikt als het niet is ingesteld op een specifieke FAQ of categorie.',
                'open_graph' => 'Open Graph',
                'open_graph_comment' => 'Open Graph-tags gebruikt voor het delen op sociale media. Wordt standaard gebruikt als het niet is ingesteld op een specifieke FAQ of categorie.',
            ],
            'tabs' => [
                'general' => 'Algemeen',
                'meta' => 'Meta',
                'open_graph' => 'Open Graph',
            ],
        ],
    ],

    'permissions' => [
        'access_settings' => 'Toegang tot FAQ-instellingen',
        'manage_categories' => 'Beheer FAQ-categorieën',
        'manage_faqs' => 'Beheer FAQs',
    ],

    'enums' => [
        'featured_status' => [
            '0' => 'Niet uitgelicht',
            '1' => 'Uitgelicht',
        ],
        'publish_status' => [
            0 => 'Niet gepubliceerd',
            1 => 'Gepubliceerd',
            2 => 'In bewerking'
        ]
    ],
];
