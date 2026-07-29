<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Foire aux questions. Questions et réponses. Classez-les par catégorie, attribuez-leur un statut « en vedette » et gérez celles qui s’affichent sur votre site..'
    ],

    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Catégories'
    ],

    'controllers' => [
        'buttons' => [
            'return' => 'Retour'
        ],
        'faqs' => [
            'new' => 'Nouvelle FAQ'
        ],
        'categories' => [
            'new' => 'Nouvelle catégorie'
        ]
    ],

    'components' => [
        'categories' => [
            'title' => 'Catégories de FAQ',
            'description' => 'Liste des catégories de FAQ',
            'all_label' => 'Toutes les questions',
            'properties' => [
                'links' => 'Liens',
                'category_page' => [
                    'title' => 'Page de catégorie',
                    'description' => 'Page qui affiche les FAQs d’une catégorie'
                ],
                'faq_page' => [
                    'title' => 'Page d’aperçu',
                    'description' => 'Page qui affiche toutes les FAQs'
                ],
                'slug' => [
                    'title' => 'Slug de catégorie',
                    'description' => 'Slug de la catégorie active, utilisé pour la mettre en évidence dans la liste'
                ],
            ],
        ],
        'faqs' => [
            'title' => 'FAQs',
            'description' => 'Liste des FAQs',
            'search_button' => 'Rechercher',
            'search_placeholder' => 'Que cherchez-vous ?',
            'no_results' => 'Aucune FAQ trouvée.',
            'properties' => [
                'search_group' => 'Recherche',
                'category' => [
                    'title' => 'Catégorie',
                    'description' => 'Choisissez la catégorie de FAQs à afficher',
                    'all' => 'Toutes les catégories',
                    'no_category_label' => 'Aucune catégorie',
                ],
                'featured' => [
                    'title' => 'FAQs',
                    'description' => 'Choisissez les FAQs à afficher',
                    'options' => [
                        0 => 'Toutes sauf à la une',
                        1 => 'À la une uniquement',
                        2 => 'Toutes les FAQs'
                    ],
                ],
                'minSearchResults' => [
                    'title' => 'Résultats minimum pour la recherche',
                    'description' => 'Nombre minimum de résultats à afficher dans le champ de recherche. Doit être un nombre.',
                    'validationMessage' => 'Doit être un nombre',
                ],
                'search' => [
                    'title' => 'Reccherche',
                    'description' => 'Activer le champ de recherche pour les FAQs.',
                ],
                'sort' => [
                    'title'       => 'Tri',
                    'description' => 'Choisissez l’ordre d’affichage des FAQs',
                    'options'     => [
                        'category_id_asc'  => 'Catégorie croissante',
                        'category_id_desc' => 'Catégorie décroissante',
                        'created_at_asc'   => 'Créé le croissant',
                        'created_at_desc'  => 'Créé le décroissant',
                    ]
                ],
                'translated' => [
                    'title' => 'FAQs traduites uniquement',
                    'description' => 'Affichez uniquement les FAQs traduites dans la langue actuelle',
                ],
            ],
        ]
    ],

    'models' => [
        'general' => [
            'id' => 'ID',
            'name' => 'Nom',
            'none_options' => '-- Aucune --',
            'slug' => 'Slug',
            'total' => 'Total',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
            'featured_status' => [
                'title' => 'À la une',
                'featured' => 'À la une',
                'not_featured' => 'Non mise en avant'
            ],
            'published_status' => [
                'title' => 'Publié',
                'published' => 'Publié',
                'not_published' => 'Caché',
                'in_draft' => 'En cours'
            ],
        ],
        'category' => [
            'label' => 'Catégorie',
            'label_plural' => 'Catégories',
            'published_status' => [
                'title' => 'Statut publication',
                'published' => 'Publié',
                'not_published' => 'Caché',
                'draft' => 'En cours',
            ],
        ],
        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQs',
            'question' => 'Question',
            'answer' => 'Réponse',
        ]
    ],

    'permissions' => [
        'manage_categories' => 'Gérer les catégories de FAQ',
        'manage_faqs' => 'Gérer les FAQs'
    ]
];
