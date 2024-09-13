<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Cache\SimpleCacheManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Utils\Log;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use JetBrains\PhpStorm\NoReturn;
use function is_null;

/**
 * Class: @VotesConfigController
 * @package Votes
 * @author Teyir & Zomb
 * @version 1.0
 */
class VotesConfigController extends AbstractController
{
    #[Link(path: '/', method: Link::GET, scope: '/cmw-admin/votes')]
    #[Link('/config', Link::GET, [], '/cmw-admin/votes')]
    private function votesConfig(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        $config = VotesConfigModel::getInstance()->getConfig();

        View::createAdminView('Votes', 'config')
            ->addVariableList(['config' => $config])
            ->view();
    }

    #[NoReturn] #[Link('/config', Link::POST, [], '/cmw-admin/votes')]
    private function votesConfigPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        [$topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi] = Utils::filterInput('topShow',
            'reset', 'autoTopRewardActive', 'autoTopReward', 'api');

        $needLogin = isset($_POST['needLogin']) ? 1 : 0;

        $updatedConfig = VotesConfigModel::getInstance()->updateConfig(
            $topShow,
            $reset,
            $autoTopRewardActive,
            $autoTopReward,
            $enableApi,
            $needLogin,
        );

        if (is_null($updatedConfig)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('core.toaster.internalError'),
            );

            Redirect::redirectPreviousRoute();
        }

        //Update cache
        SimpleCacheManager::storeCache($updatedConfig->toJson(), 'config', 'Votes');

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('core.toaster.config.success'),
        );

        Redirect::redirectPreviousRoute();
    }
}
