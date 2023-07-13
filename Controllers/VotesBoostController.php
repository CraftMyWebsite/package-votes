<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Env\EnvManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Manager\Router\Link;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\CheckVotesModel;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Model\Votes\VotesModel;
use CMW\Model\Votes\VotesRewardsModel;
use CMW\Model\Votes\VotesSitesModel;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use CMW\Manager\Views\View;
use CMW\Utils\Website;
use JsonException;


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