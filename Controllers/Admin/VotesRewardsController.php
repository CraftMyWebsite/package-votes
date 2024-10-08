<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Interface\Votes\IRewardMethod;
use CMW\Manager\Filter\FilterManager;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Loader\Loader;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\VotesRewardsModel;
use CMW\Utils\Redirect;
use JetBrains\PhpStorm\NoReturn;
use JsonException;

/**
 * Class: @VotesRewardsController
 * @package Votes
 * @author Teyir & Zomb
 * @version 0.0.1
 */
class VotesRewardsController extends AbstractController
{
    #[Link('/rewards', Link::GET, [], '/cmw-admin/votes')]
    private function votesRewards(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.edit');

        $rewards = VotesRewardsModel::getInstance()->getRewards();

        View::createAdminView('Votes', 'rewards')
            ->addVariableList(['rewards' => $rewards, 'rewardMethods' => $this->getRewardMethods()])
            ->addStyle('Admin/Resources/Assets/Css/simple-datatables.css')
            ->addScriptAfter('Admin/Resources/Vendors/Simple-datatables/simple-datatables.js',
                'Admin/Resources/Vendors/Simple-datatables/config-datatables.js')
            ->view();
    }

    #[NoReturn]
    #[Link('/rewards/add', Link::POST, [], '/cmw-admin/votes')]
    private function addRewardPost(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.add');

        $rewardType = FilterManager::filterInputStringPost('reward_type_selected', 50);
        $title = FilterManager::filterInputStringPost('title');

        $advancedAction = $this->getRewardMethodByVarName($rewardType)?->execRewardActionLogic();

        if (!is_null($advancedAction)) {
            $action = $advancedAction;
        } else {
            $action = filter_input(INPUT_POST, $rewardType);
        }

        VotesRewardsModel::getInstance()->addReward($title, $action, $rewardType);

        Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
            LangManager::translate('votes.toaster.reward.add.success', ['name' => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[NoReturn]
    #[Link('/rewards/delete/:id', Link::GET, ['id' => '[0-9]+'], '/cmw-admin/votes')]
    private function deleteRewardPostAdmin(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.delete');

        $title = VotesRewardsModel::getInstance()->getRewardById($id)?->getTitle();

        VotesRewardsModel::getInstance()->deleteReward($id);

        Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
            LangManager::translate('votes.toaster.reward.delete.success', ['name' => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link('/rewards/edit/:id', Link::GET, [], '/cmw-admin/votes')]
    private function votesRewardsEdit(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.edit');

        $rewards = VotesRewardsModel::getInstance()->getRewardById($id);

        View::createAdminView('Votes', 'editRewards')
            ->addVariableList(['rewards' => $rewards, 'rewardMethods' => $this->getRewardMethods()])
            ->view();
    }

    #[NoReturn]
    #[Link('/rewards/edit/:id', Link::POST, [], '/cmw-admin/votes')]
    private function editRewardPost(int $id): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.edit');

        $rewardType = FilterManager::filterInputStringPost('reward_type_selected', 50);
        $title = FilterManager::filterInputStringPost('title');

        $advancedAction = $this->getRewardMethodByVarName($rewardType)?->execRewardActionLogic();
        if (!is_null($advancedAction)) {
            $action = $advancedAction;
        } else {
            $action = $rewardType;
        }

        VotesRewardsModel::getInstance()->updateReward($id, $title, $action, $rewardType);

        Flash::send(Alert::SUCCESS, 'Votes', 'Récompenses modifié !');

        Redirect::redirectPreviousRoute();
    }

    // Return the reward with a specific ID
    #[Link('/rewards/get', Link::POST, [], '/cmw-admin/votes', secure: false)]
    private function getReward(): void
    {
        UsersController::redirectIfNotHavePermissions('core.dashboard', 'votes.rewards.edit');

        /* Error section */
        if (empty(filter_input(INPUT_POST, 'id'))) {
            try {
                echo json_encode(['response' => 'ERROR-EMPTY_ID'], JSON_THROW_ON_ERROR);
            } catch (JsonException) {
            }
        } else {
            echo VotesRewardsModel::getInstance()->getRewardById(filter_input(INPUT_POST, 'id'))?->getAction();
        }
    }

    /*
     * PUBLIC METHODS
     */

    /**
     * @return \CMW\Interface\Votes\IRewardMethod[]
     */
    public function getRewardMethods(): array
    {
        return Loader::loadImplementations(IRewardMethod::class);
    }

    /**
     * @param string $varName
     * @return \CMW\Interface\Votes\IRewardMethod|null
     */
    public function getRewardMethodByVarName(string $varName): ?IRewardMethod
    {
        foreach ($this->getRewardMethods() as $rewardMethod) {
            if ($rewardMethod->varName() === $varName) {
                return $rewardMethod;
            }
        }
        return null;
    }
}
