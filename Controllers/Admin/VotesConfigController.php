<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;

/**
 * Class: @VotesConfigController
 * @package Votes
 * @author Teyir & Zomb
 * @version 1.0
 */
class VotesConfigController extends AbstractController
{
    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/votes")]
    #[Link("/config", Link::GET, [], "/cmw-admin/votes")]
    public function votesConfig(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.configuration");

        $config = VotesConfigModel::getInstance()->getConfig();

        View::createAdminView('Votes', 'config')
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/config", Link::POST, [], "/cmw-admin/votes")]
    public function votesConfigPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.configuration");

        [$topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi] = Utils::filterInput("topShow",
            "reset", "autoTopRewardActive", "autoTopReward", "api");

        $needLogin = isset($_POST['needLogin']) ? 1 : 0;

        VotesConfigModel::getInstance()->updateConfig($topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi, $needLogin);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("core.toaster.config.success"));

        Redirect::redirectPreviousRoute();
    }
}