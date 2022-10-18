<?php

return [
    "dashboard" => [
        "title" => [
            "config" => "Votes - Configuration",
            "stats" => "Votes - Stats de votes",
            "add_site" => "Votes - Ajouter un site",
            "list_sites" => "Votes - Liste des sites",
            "rewards" => "Votes - Récompenses",
        ],

        "desc" => "Gérez votre plugin de votes.",

        "add_site" => [
            "card_title" => "Ajouter un site de votes",
            "placeholder" => [
                "title" => "Titre du site de votes",
                "time" => "Temps entre chaques votes en minutes (exemple: 90)",
                "id_unique" => "Id unique de votre site (exemple: 2154)",
                "url" => "Liens vers la page de votes",
                "rewards" => "Récompenses",
            ],
            "btn" => [
                "sitescomp" => "Afficher les sites de votes compatibles",
                "testid" => "Tester l'Id",
            ],
            "sitescomp" => [
                "modal_title" => "Liste des sites de votes compatibles",
                "websites_title" => "Sites de vote Minecraft",
                "request" => "Demande d'ajouts de nouveaux sites"
            ],
        ],

        "list_sites" => [
            "title" => "Cliquez sur le site que vous souhaitez éditer",
            "del_site" => [
                "modal" => [
                    "title" => "Suppression du site",
                    "body" => "Êtes-vous certains de vouloir supprimer ce site ?"
                ],
            ],
            "noreward" => "Vous ne possédez aucune récompense"
        ],

        "config" => [
            "reset" => [
                "0" => "Pas de reset de votes",
                "1" => "Mensuel",
            ],
            "placeholder" => [
                "reset" => "Sélection du mode de reset des modes",
                "top_show" => "Sélection du nombre de joueurs à afficher au classement sur votre site",
            ],
        ],

        "stats" => [
            "totals" => "Votes totaux",
            "month" => "Votes du mois en cours",
            "week" => "Votes de là semaine en cours",
            "day" => "Votes d'aujourd'hui",
        ],

        "rewards" => [
            "votepoints" => [
                "name" => "Votepoints",
                "random" => " aléatoires",
            ],
            "add" => [
                "title" => "Ajouter une nouvelle récompense",
                "placeholder" => [
                    "title" => "Nom de la récompense",
                    "type" => "Type de la récompense",
                    "type_select" => "Choisissez une récompense",
                    "amount" => "Montant",
                    "amount_minimum" => "Montant minimum",
                    "amount_maximum" => "Montant maximum",
                ],
            ],
            "list" => [
                "title" => "Cliquez sur la récompense que vous souhaitez éditer",
            ],
            "del" => [
                "body" => "Êtes-vous certains de vouloir supprimer cette récompense ?",
                "title" => "Suppression de la récompense"
            ],
        ],
    ],
];