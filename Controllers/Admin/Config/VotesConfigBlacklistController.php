<?php

namespace CMW\Controller\Votes\Admin\Config;

use CMW\Controller\Users\UsersController;
use CMW\Controller\Users\UsersSessionsController;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\Config\VotesConfigBlacklistModel;
use CMW\Utils\Redirect;
use JetBrains\PhpStorm\NoReturn;
use function is_null;

/**
 * Class: @VotesConfigBlacklistController
 * @package Votes
 */
class VotesConfigBlacklistController extends AbstractController
{
    #[Link('/blacklist', Link::GET, scope: '/cmw-admin/votes/config')]
    private function blacklist(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        $users = UsersModel::getInstance()->getUsers();
        $blacklists = VotesConfigBlacklistModel::getInstance()->getBlacklists();

        View::createAdminView('Votes', 'Config/blacklist')
            ->addStyle('Admin/Resources/Assets/Css/simple-datatables.css')
            ->addScriptAfter('Admin/Resources/Vendors/Simple-datatables/simple-datatables.js',
                'Admin/Resources/Vendors/Simple-datatables/config-datatables.js')
            ->addVariableList(['users' => $users, 'blacklists' => $blacklists])
            ->view();
    }

    #[NoReturn] #[Link('/blacklist', Link::POST, scope: '/cmw-admin/votes/config')]
    private function addBlacklist(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        if (!isset($_POST['userId'])) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('votes.toaster.empty_input')
            );
            Redirect::redirectPreviousRoute();
        }

        $userId = FilterManager::filterInputIntPost('userId');
        $user = UsersModel::getInstance()->getUserById($userId);

        if (is_null($user)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('votes.toaster.user_not_found')
            );
            Redirect::redirectPreviousRoute();
        }

        $blacklists = VotesConfigBlacklistModel::getInstance()->getBlacklists();

        foreach ($blacklists as $blacklist) {
            if ($blacklist->getUserId() === $userId) {
                Flash::send(
                    Alert::ERROR,
                    LangManager::translate('core.toaster.error'),
                    LangManager::translate('votes.toaster.user_already_blacklisted', ['pseudo' => $user->getPseudo()])
                );
                Redirect::redirectPreviousRoute();
            }
        }

        $author = UsersSessionsController::getInstance()->getCurrentUser();

        if (!VotesConfigBlacklistModel::getInstance()->addBlacklist($userId, $author?->getId())) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('votes.toaster.error_add_blacklist')
            );
        }

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('votes.toaster.success_add_blacklist', ['pseudo' => $user->getPseudo()])
        );

        Redirect::redirectPreviousRoute();
    }

    #[Link('/blacklist/delete/:userId', Link::GET, ['userId' => '[0-9]+'], '/cmw-admin/votes/config')]
    private function removeBlacklist(int $userId): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.configuration');

        $user = UsersModel::getInstance()->getUserById($userId);

        if (is_null($user)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('votes.toaster.user_not_found')
            );
            Redirect::redirectPreviousRoute();
        }

        if (!VotesConfigBlacklistModel::getInstance()->removeBlacklist($userId)) {
            Flash::send(
                Alert::ERROR,
                LangManager::translate('core.toaster.error'),
                LangManager::translate('votes.toaster.error_remove_blacklist', ['pseudo' => $user->getPseudo()])
            );
        }

        Flash::send(
            Alert::SUCCESS,
            LangManager::translate('core.toaster.success'),
            LangManager::translate('votes.toaster.success_remove_blacklist', ['pseudo' => $user->getPseudo()])
        );

        Redirect::redirectPreviousRoute();
    }
}
