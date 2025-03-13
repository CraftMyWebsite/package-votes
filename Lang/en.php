<?php

return [
    'votes' => 'Votes',
    'vote' => 'Vote',
    'dashboard' => [
        'title' => [
            'config' => 'Configuration',
            'stats' => 'Statistics',
            'add_site' => 'Add a site',
            'manage_site' => 'Site Management',
            'list_sites' => 'List of sites',
            'rewards' => 'Rewards',
            'settings' => 'Settings',
        ],
        'table' => [
            'name' => 'Name',
            'time' => 'Voting time',
            'url' => 'URL',
            'reward' => 'Rewards',
            'action' => 'Actions',
            'min' => 'minutes',
            'type' => 'Type of rewards',
        ],
        'modal' => [
            'editing' => 'Edition of :',
            'delete' => 'Delete of :',
            'deletealert' => 'The deletion of the voting site is permanent.',
            'deletealertreward' => 'The deletion of the reward is final !<br>If this is used for a site, be sure to change it.',
        ],
        'desc' => 'Manage your voting plugin',
        'add_site' => [
            'card_title' => 'Add a voting site',
            'input' => [
                'title' => 'Title voting site',
                'time' => 'Time between each vote in minutes',
                'id_unique' => 'Id or API key of the voting site',
                'url' => 'Links to the voting page',
                'rewards' => 'Rewards when voting',
            ],
            'placeholder' => [
                'title' => 'Top Servers',
                'time' => '90',
                'id_unique' => '2154 / B5CYHZSRVE3S',
                'url' => 'https://top-serveurs.net/minecraft/vote/name',
                'rewards' => 'Rewards',
            ],
            'btn' => [
                'sitescomp' => 'Compatible Sites',
                'testid' => 'Test the ID',
            ],
            'sitescomp' => [
                'modal_title' => 'List of compatible voting sites',
                'websites_title' => 'Minecraft Voting Sites',
                'request' => 'Request for additions of new sites',
            ],
        ],
        'list_sites' => [
            'title' => 'Click on the site you want to edit',
            'del_site' => [
                'modal' => [
                    'title' => 'Delete the site',
                    'body' => 'Are you sure you want to delete this site?',
                ],
            ],
            'tooltip' => [
                'rewards' => 'Choose the reward you previously configured. Click on the icon to go directly to the rewards configuration page',
            ],
            'noreward' => 'No rewards',
        ],
        'config' => [
            'reset' => [
                '0' => 'No vote reset set',
                '1' => 'Monthly',
                '2' => 'Weekly',
            ],
            'enable_api' => [
                '0' => 'Disable local API',
                '1' => 'Enable local API',
            ],
            'placeholder' => [
                'reset' => 'Voting reset mode',
                'top_show' => 'Number of voters to display',
                'enable_api' => "Activate the site's API",
            ],
            'needLogin' => 'User need to be logged',
            'blacklist' => [
                'title' => 'Votes - Blacklist',
                'description' => 'Votes - Blacklist',
                'heading' => 'Votes - Manage the blacklist',
            ],
        ],
        'stats' => [
            'somenumber' => 'Some numbers',
            'totals' => 'Totals',
            'month' => 'This month',
            'week' => 'This week',
            'day' => 'Today',
            '3pastsMonths' => 'Votes from the last 3 months',
            'sites_totals' => 'Votes per site (totals)',
            'sites_current' => 'Votes by site (current month)',
            'top_current' => 'Top voters of the current month',
            'top_totals' => 'Top total voters',
            'top_pastMonth' => "Previous month's top voters",
            'monthlyVotes' => 'Monthly votes',
        ],
        'rewards' => [
            'votepoints' => [
                'name' => 'Vote points',
                'fixed' => ' Unique',
                'random' => ' Random',
            ],
            'minecraft' => [
                'commands' => 'Minecraft Commands',
                'servers' => 'Servers',
                'placeholder' => [
                    'commands' => "Order(s), separate your orders with '|'",
                ],
            ],
            'add' => [
                'title' => 'Add a reward',
                'placeholder' => [
                    'title' => 'Reward name',
                    'type' => 'Type of reward',
                    'type_select' => 'Choose a reward',
                    'amount' => 'Amount',
                    'amount_minimum' => 'Amount minimum',
                    'amount_maximum' => 'Amount maximum',
                ],
            ],
            'list' => [
                'title' => 'Click on the reward you want to edit',
            ],
            'del' => [
                'body' => 'Are you sure you want to remove this reward?',
                'title' => 'Removal of reward',
            ],
        ],
    ],
    'toaster' => [
        'site' => [
            'add' => [
                'success' => 'Site <b>%name%</b> successfully added',
            ],
            'edit' => [
                'success' => 'Site <b>%name%</b> successfully modified',
            ],
            'delete' => [
                'success' => 'Site <b>%name%</b> successfully deleted',
            ],
            'test_id' => [
                'success' => 'Unique id validate !',
                'error' => 'Unique id not validate',
                'empty_input' => 'Please fill in all the inputs !',
            ],
        ],
        'reward' => [
            'add' => [
                'success' => 'Récompense <b>%name%</b> successfully added',
            ],
            'delete' => [
                'success' => 'Récompense <b>%name%</b> successfully deleted',
            ],
        ],
        'user_not_found' => 'User not found',
        'user_already_blacklisted' => 'User %pseudo% already blacklisted',
        'error_add_blacklist' => 'Error adding the user to the blacklist',
        'success_add_blacklist' => 'User %pseudo% successfully added to the blacklist',
        'error_remove_blacklist' => 'Error removing %pseudo% from the blacklist',
        'success_remove_blacklist' => 'User %pseudo% successfully removed from the blacklist',
    ],
    'permissions' => [
        'votes' => [
            'configuration' => 'Manage configuration',
            'site' => [
                'list' => 'Show',
                'add' => 'Add',
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
            'rewards' => [
                'edit' => 'Edit',
                'add' => 'Add',
                'delete' => 'Delete',
            ],
            'boost' => 'Manage boosts',
        ],
    ],
    'menu' => [
        'config' => [
            'title' => 'Configuration',
            'general' => 'General',
            'blacklist' => 'Blacklist',
        ],
        'reward' => 'Rewards',
        'sites' => 'Site management',
        'stats' => 'Statistics',
    ],
];
