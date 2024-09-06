<?php

namespace CMW\Controller\Votes\Public;

use CMW\Controller\Users\UsersController;
use CMW\Controller\Votes\Admin\VotesRewardsController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\CheckVotesModel;
use CMW\Model\Votes\VotesModel;
use CMW\Model\Votes\VotesSitesModel;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Utils\Redirect;
use CMW\Utils\Website;
use JsonException;

use function is_null;

/**
 * Class: @VotesPublicController
 * @package Votes
 * @author Teyir & Zomb
 * @version 1.0
 */
class VotesPublicController extends AbstractController
{
    #[Link('/vote', Link::GET)]
    public function votesPublic(): void
    {
        $sites = VotesSitesModel::getInstance()->getSites();

        $topCurrent = VotesStatsModel::getInstance()->getActualTop();
        $topGlobal = VotesStatsModel::getInstance()->getGlobalTop();

        // Include the Public view file ("Public/Themes/$themePath/Views/Votes/main.view.php")
        $view = new View('Votes', 'main');

        $view->addVariableList(['sites' => $sites,
            'topCurrent' => $topCurrent, 'topGlobal' => $topGlobal]);
        $view->addStyle('Admin/Resources/Vendors/Izitoast/iziToast.min.css');
        $view->addScriptAfter('Admin/Resources/Vendors/Izitoast/iziToast.min.js', 'App/Package/Votes/Views/Resources/Js/public.js', 'App/Package/Votes/Views/Resources/Js/VotesStatus.js', 'App/Package/Votes/Views/Resources/Js/VotesLogic.js');
        $view->view();
    }

    #[Link('/vote/testsend/:id', Link::GET, ['id' => '[0-9]+'])]
    private function votesWebsiteTestPublic(int $id): void
    {
        if (UsersController::isAdminLogged()) {
            $userId = UsersModel::getCurrentUser()?->getId();
            $reward = VotesSitesModel::getInstance()->getSiteById($id)?->getRewards();
            if (!is_null($reward)) {
                $site = VotesSitesModel::getInstance()->getSiteById($id);
                if (!is_null($site)) {
                    VotesRewardsController::getInstance()->getRewardMethodByVarName($reward->getVarName())?->execReward($reward, $site, $userId);
                    Flash::send(Alert::SUCCESS, 'Votes', 'Récompense envoyée !');
                    Redirect::redirectPreviousRoute();
                }
            } else {
                Flash::send(Alert::SUCCESS, 'Votes', 'Merci pour votre vote !');
                Redirect::redirectPreviousRoute();
            }
        } else {
            Flash::send(Alert::ERROR, 'Votes', "Vous n'êtes pas en mesure de réaliser ce test !");
            Redirect::redirectPreviousRoute();
        }
    }

    #[Link('/vote/send/:id', Link::GET, ['id' => '[0-9]+'])]
    public function votesWebsitePublic(int $id): void
    {
        $userId = UsersModel::getCurrentUser()?->getId();

        if ($userId === null) {
            echo 'User not Found.';
            return;
        }

        $reward = VotesSitesModel::getInstance()->getSiteById($id)?->getRewards() ?? null;

        try {
            // First, check if the player can vote.
            if (CheckVotesModel::getInstance()->isVoteSend(VotesSitesModel::getInstance()->getSiteById($id)?->getUrl(),
                    VotesSitesModel::getInstance()->getSiteById($id)?->getIdUnique(), Website::getClientIp())) {
                // Check if the player has a vote stored
                if (VotesModel::getInstance()->playerHasAVoteStored($userId, $id)) {
                    // Check if we can validate this vote
                    if (VotesModel::getInstance()->validateThisVote($userId, $id)) {
                        VotesModel::getInstance()->storeVote($userId, $id);
                        if (!is_null($reward)) {
                            $site = VotesSitesModel::getInstance()->getSiteById($id);
                            if (!is_null($site)) {
                                VotesRewardsController::getInstance()->getRewardMethodByVarName($reward->getVarName())?->execReward($reward, $site, $userId);
                            }
                        }
                        $this->returnData('send', true);
                    } else {
                        $this->returnData('already_vote', true);
                    }
                } else {  // The player don't have any vote for this website.
                    VotesModel::getInstance()->storeVote($userId, $id);

                    if (!is_null($reward)) {
                        $site = VotesSitesModel::getInstance()->getSiteById($id);
                        if (!is_null($site)) {
                            VotesRewardsController::getInstance()->getRewardMethodByVarName($reward->getVarName())?->execReward($reward, $site, $userId);
                        }
                    }

                    $this->returnData('send', true);
                }
            } else {  // The player has already voted.
                $this->returnData('not_send');
            }
        } catch (JsonException $e) {
            echo 'Internal Error. ' . $e;
        }
    }

    private function returnData(string $toReturn, bool $isFinal = false): void
    {
        try {
            print (json_encode($toReturn, JSON_THROW_ON_ERROR));

            if ($isFinal) {
                die();
            }
        } catch (JsonException $e) {
            echo "Can't return data. " . $e;
        }
    }

    #[Link('/vote/geturl/:id', Link::GET, ['id' => '[0-9]+'])]
    public function votesGetWebsiteUrlPublic(int $id): void
    {
        print VotesSitesModel::getInstance()->getSiteById($id)?->getUrl();
    }
}
