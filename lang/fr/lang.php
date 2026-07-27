<?php

return [
    'plugin' => [
        'name' => 'FAQ',
        'description' => 'Foire aux questions. Questions et réponses. Classez-les par catégorie, attribuez-leur un statut « en vedette » et gérez celles qui s’affichent sur votre site..'
    ],
    'button' => [
        'return' => 'Retour'
    ],
    'menu' => [
        'faqs' => 'FAQs',
        'categories' => 'Catégories'
    ],
    'title' => [
        'faqs' => 'FAQ',
        'categories' => 'Catégorie'
    ],
    'new' => [
        'faqs' => 'Nouvelle FAQ',
        'categories' => 'Nouvelle catégorie'
    ],
    'form' => [
        'total' => 'TOTAL',
        'id' => 'ID',
        'name' => 'Nom',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'question' => 'Question',
        'answer' => 'Réponse',
        'category' => 'Catégorie',
        'no_category' => '-',
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
        ]
    ],
    'component' => [
        'title' => 'FAQs',
        'description' => 'Liste des FAQs',
        'settings' => [
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
            'category' => [
                'title' => 'Catégorie',
                'description' => 'Choisissez la catégorie de FAQs à afficher',
                'all' => 'Toutes les catégories',
                'no_category_label' => 'Aucune catégorie',
            ],
            'featured' => [
                'title' => 'FAQs',
                'description' => 'Choisissez les FAQs à afficher',
                'all' => 'Toutes les FAQs',
                'featured' => 'FAQs à la une uniquement',
                'not_featured' => 'Toutes les FAQs sauf celles à la une'
            ],
            'translated' => [
                'title' => 'FADs traduites uniquement',
                'description' => 'Affichez uniquement les FAQs traduites dans la langue actuelle',
            ],
            'search' => [
                'title' => 'Reccherche',
                'description' => 'Activer le champ de recherche pour les FAQs.',
                'button_label' => 'Rechercher',
                'input_placeholder' => 'Que cherchez-vous ?'
            ],
            'minSearchResults' => [
                'title' => 'Résultats minimum pour la recherche',
                'description' => 'Nombre minimum de résultats à afficher dans le champ de recherche. Doit être un nombre.',
                'validationMessage' => 'Doit être un nombre'
            ]
        ]
    ],
    'permission' => [
        'faq' => 'Gérer la FAQ'
    ]
];
