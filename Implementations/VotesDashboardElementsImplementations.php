<?php

namespace CMW\Implementation\Votes;

use CMW\Interface\Core\IDashboardElements;
use CMW\Manager\Env\EnvManager;

class VotesDashboardElementsImplementations implements IDashboardElements
{

    public function widgets(): void
    {
        require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/Views/Elements/dashboard.inc.view.php";
    }
}