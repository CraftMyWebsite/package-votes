<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;


/**
 * Class: @VotesBoostController
 * @package Votes
 * @author Teyir
 * @version 1.0
 */
class VotesBoostController extends AbstractController
{
    #[Link("/", Link::GET, [], "/cmw-admin/votes/boost")]
    public function votesConfig(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.boost");


        View::createAdminView('Votes', 'boost')
            ->view();
    }

}