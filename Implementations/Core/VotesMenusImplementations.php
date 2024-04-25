<?php

namespace CMW\Implementation\Votes\Core;

use CMW\Interface\Core\IMenus;
use CMW\Manager\Lang\LangManager;

class VotesMenusImplementations implements IMenus
{

    public function getRoutes(): array
    {
        return [
            LangManager::translate('votes.vote') => 'vote',
        ];
    }

    public function getPackageName(): string
    {
        return 'Votes';
    }
}