<?php

namespace CMW\Package\Votes;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return "Votes";
    }

    public function version(): string
    {
        return "0.0.4";
    }

    public function authors(): array
    {
        return ["Teyir"];
    }

    public function isGame(): bool
    {
        return false;
    }

    public function isCore(): bool
    {
        return false;
    }

    public function menus(): ?array
    {
        return [
            new PackageMenuType(
                lang: "fr",
                icon: "fas fa-vote-yea",
                title: "Votes",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Configuration',
                        permission: 'votes.configuration',
                        url: 'votes/config',
                    ),
                    new PackageSubMenuType(
                        title: 'Gestion des sites',
                        permission: 'votes.site.list',
                        url: 'votes/sites/list',
                    ),
                    new PackageSubMenuType(
                        title: 'Récompenses',
                        permission: 'votes.rewards.edit',
                        url: 'votes/rewards',
                    ),
                    new PackageSubMenuType(
                        title: 'Statistiques',
                        permission: 'votes.todo', //TODO PERMS
                        url: 'votes/stats',
                    ),
                    new PackageSubMenuType(
                        title: 'Multiplicateur',
                        permission: 'votes.boost',
                        url: 'votes/boost',
                    ),
                    new PackageSubMenuType(
                        title: 'VoteShop',
                        permission: 'votes.todo', //TODO PERMS
                        url: 'votes/voteshop',
                    ),
                ]
            ),
            new PackageMenuType(
                lang: "en",
                icon: "fas fa-vote-yea",
                title: "Votes",
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Configuration',
                        permission: 'votes.configuration',
                        url: 'votes/config',
                    ),
                    new PackageSubMenuType(
                        title: 'Manage sites',
                        permission: 'votes.site.list',
                        url: 'votes/sites/list',
                    ),
                    new PackageSubMenuType(
                        title: 'Rewards',
                        permission: 'votes.rewards.edit',
                        url: 'votes/rewards',
                    ),
                    new PackageSubMenuType(
                        title: 'Statistics',
                        permission: 'votes.todo', //TODO PERMS
                        url: 'votes/stats',
                    ),
                    new PackageSubMenuType(
                        title: 'Multiplier',
                        permission: 'votes.boost',
                        url: 'votes/boost',
                    ),
                    new PackageSubMenuType(
                        title: 'VoteShop',
                        permission: 'votes.todo', //TODO PERMS
                        url: 'votes/voteshop',
                    ),
                ]
            ),
        ];
    }
}