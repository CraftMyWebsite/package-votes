<?php

return [
    'votes' => 'Votes',
    'vote' => 'Vote',
    'dashboard' => [
        'title' => [
            'config' => 'Configuration',
            'stats' => 'Statistiques',
            'add_site' => 'Ajouter un site',
            'manage_site' => 'Gestion des sites',
            'list_sites' => 'Liste des sites',
            'rewards' => 'Récompenses',
            'settings' => 'Réglages',
        ],
        'table' => [
            'name' => 'Nom',
            'time' => 'Temps de vote',
            'url' => 'URL',
            'reward' => 'Récompenses',
            'action' => 'Actions',
            'min' => 'minutes',
            'type' => 'Type de récompense',
        ],
        'modal' => [
            'editing' => 'Édition de :',
            'delete' => 'Supression de :',
            'deletealert' => 'La suppression du site de vote est définitive.',
            'deletealertreward' => 'La suppression de la récompense est définitive !<br>Si celle-ci est utilisé pour un site veillez à le changer.',
        ],
        'desc' => 'Gérez votre plugin de votes.',
        'add_site' => [
            'card_title' => 'Ajouter un site de votes',
            'input' => [
                'title' => 'Titre du site de votes',
                'time' => 'Temps entre chaque votes en minutes',
                'id_unique' => 'Identifiant ou clé API du site de vote',
                'url' => 'Lien vers la page de votes',
                'rewards' => 'Récompenses lors du vote',
            ],
            'placeholder' => [
                'title' => 'Top Serveurs',
                'time' => '90',
                'id_unique' => '2154 / B5CYHZSRVE3S',
                'url' => 'https://top-serveurs.net/minecraft/vote/name',
                'rewards' => 'Récompenses',
            ],
            'btn' => [
                'sitescomp' => 'Sites compatibles',
                'testid' => "Tester l'Identifiant",
            ],
            'sitescomp' => [
                'modal_title' => 'Liste des sites de votes compatibles',
                'websites_title' => 'Sites de vote Minecraft',
                'request' => "Demande d'ajouts de nouveaux sites",
            ],
        ],
        'list_sites' => [
            'title' => 'Cliquez sur le site que vous souhaitez éditer',
            'del_site' => [
                'modal' => [
                    'title' => 'Suppression du site',
                    'body' => 'Êtes-vous certain de vouloir supprimer ce site ?',
                ],
            ],
            'tooltip' => [
                'rewards' => "Choisis la récompense que tu as précédemment configuré. Clique sur l'icône pour accéder directement sur la page de configuration des récompenses",
            ],
            'noreward' => 'Pas de récompenses',
        ],
        'config' => [
            'reset' => [
                '0' => 'Pas de reset des votes',
                '1' => 'Mensuel',
                '2' => 'Hebdomadaire',
            ],
            'enable_api' => [
                '0' => "Désactiver l'API locale",
                '1' => "Activer l'API locale",
            ],
            'placeholder' => [
                'reset' => 'Mode de réinitialisation des votes',
                'top_show' => 'Nombre de voteurs à afficher',
                'enable_api' => "Activer l'API du site",
            ],
            'needLogin' => 'Connexion requise',
        ],
        'stats' => [
            'somenumber' => 'Quelques chiffres',
            'totals' => 'Totaux',
            'month' => 'Ce mois',
            'week' => 'Cette semaine',
            'day' => "Aujourd'hui",
            '3pastsMonths' => 'Votes des 3 derniers mois',
            'sites_totals' => 'Votes par site (totaux)',
            'sites_current' => 'Votes par site (mois en cours)',
            'top_current' => 'Top voteurs du mois en cours',
            'top_totals' => 'Top voteurs depuis le début',
            'top_pastMonth' => 'Top voteurs du mois précédent',
            'monthlyVotes' => 'Votes du mois',
        ],
        'rewards' => [
            'votepoints' => [
                'name' => 'Points de vote',
                'fixed' => 'Unique',
                'random' => 'Aléatoires',
            ],
            'minecraft' => [
                'commands' => 'Commandes Minecraft',
                'servers' => 'Serveurs',
                'placeholder' => [
                    'commands' => "Commande(s), séparez vos commandes avec '|'",
                ],
            ],
            'add' => [
                'title' => 'Ajouter une récompense',
                'placeholder' => [
                    'title' => 'Nom de récompense',
                    'type' => 'Type de récompense',
                    'type_select' => 'Choisissez une récompense',
                    'amount' => 'Montant',
                    'amount_minimum' => 'Montant minimum',
                    'amount_maximum' => 'Montant maximum',
                ],
            ],
            'list' => [
                'title' => 'Cliquez sur la récompense que vous souhaitez éditer',
            ],
            'del' => [
                'body' => 'Êtes-vous certain de vouloir supprimer cette récompense ?',
                'title' => 'Suppression de la récompense',
            ],
        ],
    ],
    'toaster' => [
        'site' => [
            'add' => [
                'success' => 'Site <b>%name%</b> ajouté avec succès',
            ],
            'edit' => [
                'success' => 'Site <b>%name%</b> modifié avec succès',
            ],
            'delete' => [
                'success' => 'Site <b>%name%</b> supprimé avec succès',
            ],
            'test_id' => [
                'success' => 'Identifiant unique validé !',
                'error' => 'Identifiant unique non validé !',
                'empty_input' => 'Merci de remplir l\'ensemble des champs !',
            ],
        ],
        'reward' => [
            'add' => [
                'success' => 'Récompense <b>%name%</b> ajoutée avec succès',
            ],
            'delete' => [
                'success' => 'Récompense <b>%name%</b> supprimée avec succès',
            ],
        ],
    ],
    'permissions' => [
        'votes' => [
            'configuration' => 'Gérer les configurations',
            'site' => [
                'list' => 'Afficher',
                'add' => 'Ajouter',
                'edit' => 'Modifier',
                'delete' => 'Supprimer',
            ],
            'rewards' => [
                'edit' => 'Modifier',
                'add' => 'Ajouter',
                'delete' => 'Supprimer',
            ],
            'boost' => 'Gérez les boosts',
        ],
    ],
    'menu' => [
        'config' => 'Configuration',
        'reward' => 'Récompenses',
        'sites' => 'Gestion des sites',
        'stats' => 'Statistiques',
    ],
];
