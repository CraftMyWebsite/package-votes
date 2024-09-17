<?php

namespace CMW\Package\Votes;

use CMW\Manager\Package\IPackageConfig;
use CMW\Manager\Package\PackageMenuType;
use CMW\Manager\Package\PackageSubMenuType;

class Package implements IPackageConfig
{
    public function name(): string
    {
        return 'Votes';
    }

    public function version(): string
    {
        return '0.0.1';
    }

    public function authors(): array
    {
        return ['Teyir'];
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
                icon: 'fas fa-vote-yea',
                title: 'Votes',
                url: null,
                permission: null,
                subMenus: [
                    new PackageSubMenuType(
                        title: 'Configuration',
                        permission: 'votes.configuration',
                        url: 'votes/config',
                    ),
                    new PackageSubMenuType(
                        title: 'Récompenses',
                        permission: 'votes.rewards.edit',
                        url: 'votes/rewards',
                    ),
                    new PackageSubMenuType(
                        title: 'Gestion des sites',
                        permission: 'votes.site.list',
                        url: 'votes/site/list',
                    ),
                    new PackageSubMenuType(
                        title: 'Statistiques',
                        permission: 'votes.todo',  // TODO PERMS
                        url: 'votes/stats',
                    ),
                ]
            ),
        ];
    }

    public function requiredPackages(): array
    {
        return ['Core'];
    }

    public function uninstall(): bool
    {
        // Return true, we don't need other operations for uninstall.
        return true;
    }
}
