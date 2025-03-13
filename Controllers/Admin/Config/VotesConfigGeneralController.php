<?php

namespace CMW\Controller\Votes\Admin\Config;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Cache\SimpleCacheManager;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Utils\Redirect;
use JetBrains\PhpStorm\NoReturn;
use function is_null;

/**
 * Class: @VotesConfigGeneralController
 * @package Votes
 */
class VotesConfigGeneralController extends AbstractController
{
    #[Link('/general', Link::GET, scope: '/cmw-admin/votes/config')]
    private function generalConfig(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        $config = VotesConfigModel::getInstance()->getConfig();

        View::createAdminView('Votes', 'Config/general')
            ->addVariableList(['config' => $config])
            ->view();
    }

    #[NoReturn] #[Link('/general', Link::POST, scope: '/cmw-admin/votes/config')]
    private function generalConfigPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        $topShow = FilterManager::filterInputIntPost("top_show");
        $reset = FilterManager::filterInputStringPost("reset", 1);
        $autoTopRewardActive = FilterManager::filterInputIntPost("auto_top_reward_active");
        $autoTopReward = FilterManager::filterInputStringPost("auto_top_reward");
        $enableApi = FilterManager::filterInputIntPost("api", 1);

        $needLogin = isset($_POST['need_login']) ? 1 : 0;

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
        SimpleCacheManager::storeCache($updatedConfig->toJson(), 'general', 'Votes/Config');

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('core.toaster.config.success'),
        );

        Redirect::redirectPreviousRoute();
    }
}
