<?php

namespace CMW\Permissions\Votes;

use CMW\Manager\Lang\LangManager;
use CMW\Manager\Permission\IPermissionInit;
use CMW\Manager\Permission\PermissionInitType;

class Permissions implements IPermissionInit
{
    public function permissions(): array
    {
        return [
            new PermissionInitType(
                code: 'votes.configuration',
                description: LangManager::translate('votes.permissions.votes.configuration'),
            ),
            new PermissionInitType(
                code: 'votes.boost',
                description: LangManager::translate('votes.permissions.votes.boost'),
            ),
            new PermissionInitType(
                code: 'votes.site.list',
                description: LangManager::translate('votes.permissions.votes.site.list'),
            ),
            new PermissionInitType(
                code: 'votes.site.add',
                description: LangManager::translate('votes.permissions.votes.site.add'),
            ),
            new PermissionInitType(
                code: 'votes.site.edit',
                description: LangManager::translate('votes.permissions.votes.site.edit'),
            ),
            new PermissionInitType(
                code: 'votes.site.delete',
                description: LangManager::translate('votes.permissions.votes.site.delete'),
            ),
            new PermissionInitType(
                code: 'votes.rewards.add',
                description: LangManager::translate('votes.permissions.votes.rewards.add'),
            ),
            new PermissionInitType(
                code: 'votes.rewards.edit',
                description: LangManager::translate('votes.permissions.votes.rewards.edit'),
            ),
            new PermissionInitType(
                code: 'votes.rewards.delete',
                description: LangManager::translate('votes.permissions.votes.rewards.delete'),
            ),
        ];
    }

}