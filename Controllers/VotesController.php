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
 * Class: @VotesController
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesController extends AbstractController
{

    /* ///////////////////// CONFIG /////////////////////*/

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

        VotesConfigModel::getInstance()->updateConfig($topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("core.toaster.config.success"));

        Redirect::redirectPreviousRoute();
    }


    /* ///////////////////// SITES /////////////////////*/

    public function getCompatiblesSites(): array
    {
        $file = EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/minecraftSitesCompatibles.php";

        if(!file_exists($file)) {
            return [];
        }

        $content = include $file;

        if(!is_array($content)){
            return [];
        }

        return $content;
    }

    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function listSites(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.list");

        $sites = VotesSitesModel::getInstance()->getSites();
        $rewards = VotesRewardsModel::getInstance()->getRewards();
        $compatiblesSites = $this->getCompatiblesSites();

        View::createAdminView('Votes', 'list_sites')
            ->addVariableList(["sites" => $sites, "rewards" => $rewards, "compatiblesSites" => $compatiblesSites])
            ->addStyle("Admin/Resources/Vendors/Izitoast/iziToast.min.css",
                "Admin/Resources/Vendors/Simple-datatables/style.css",
                "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Izitoast/iziToast.min.js",
                "App/Package/Votes/Views/Resources/Js/testSitesId.js",
                "Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->view();
    }

    #[Link("/site/list", Link::POST, [], "/cmw-admin/votes")]
    public function addSiteAdminPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        [$title, $time, $idUnique, $url, $rewardsId] = Utils::filterInput("title", "time", "idUnique",
            "url", "reward");

       VotesSitesModel::getInstance()->addSite($title, $time, $idUnique, $url, $rewardsId);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.add.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function votesSitesEdit(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        $votes = VotesSitesModel::getInstance()->getSiteById(filter_input(INPUT_POST, 'siteId'));

        View::createAdminView('Votes', 'list_sites')
            ->addStyle("App/Package/Votes/Views/Resources/Vendors/Css/iziToast.min.css")
            ->addScriptAfter("App/Package/Votes/Views/Resources/Vendors/Js/iziToast.min.js")
            ->addVariableList(["votes" => $votes])
            ->view();
    }

    #[Link("/site/edit", Link::POST, [], "/cmw-admin/votes")]
    public function votesSitesEditPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        [$siteId, $title, $time, $idUnique, $url, $rewardsId] = Utils::filterInput("siteId", "title", "time",
            "idUnique", "url", "reward");

        VotesSitesModel::getInstance()->updateSite($siteId, $title, $time, $idUnique, $url, $rewardsId);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.edit.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/site/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteSitePostAdmin(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.delete");

        $title = VotesSitesModel::getInstance()->getSiteById($id)?->getTitle();

        VotesSitesModel::getInstance()->deleteSite($id);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.delete.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    /**
     * @throws \JsonException
     */
    #[Link("/site/test/id", Link::POST, [], "/cmw-admin/votes", secure: false)]
    public function checkSiteId(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        [$url, $siteId] = Utils::filterInput('url', 'site_id');

        if (Utils::containsNullValue($url, $siteId)){
            print (json_encode(["status" => "0", "toaster" =>
                ["type" => "error",
                    "title" => LangManager::translate("core.toaster.error"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.empty_input")
                ]
            ], JSON_THROW_ON_ERROR));
            return;
        }

        if (CheckVotesModel::getInstance()->testSiteId($url, $siteId)){
            print (json_encode(["status" => "1", "toaster" =>
                ["type" => "success",
                    "title" => LangManager::translate("core.toaster.success"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.success")
                ]
            ], JSON_THROW_ON_ERROR));
        } else {
            print (json_encode(["status" => "0", "toaster" =>
                ["type" => "error",
                    "title" => LangManager::translate("core.toaster.error"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.error")
                ]
            ], JSON_THROW_ON_ERROR));
        }
    }


    /* ///////////////////// REWARDS /////////////////////*/

    #[Link("/rewards", Link::GET, [], "/cmw-admin/votes")]
    public function votesRewards(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.edit");

        $rewards = VotesRewardsModel::getInstance()->getRewards();

        //TODO Check if package is installed
        $minecraftServers = (new MinecraftModel())->getServers(); //TODO Change with getInstance()

        View::createAdminView('Votes', 'rewards')
            ->addVariableList(["rewards" => $rewards, "minecraftServers" => $minecraftServers])
            ->addScriptBefore("App/Package/Votes/Views/Resources/Js/reward.js")
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css","Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js","Admin/Resources/Assets/Js/Pages/simple-datatables.js")

            ->view();
    }

    #[Link("/rewards/add", Link::POST, [], "/cmw-admin/votes")]
    public function addRewardPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.add");

        $rewardType = filter_input(INPUT_POST, "reward_type");
        $title = filter_input(INPUT_POST, "title");

        $action = "";
        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                try {
                    $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;

            case "votepoints-random":
                try {
                    $action = json_encode(array("type" => "votepoints-random",
                        "amount" => array(
                            "min" => filter_input(INPUT_POST, "amount-min"),
                            "max" => filter_input(INPUT_POST, "amount-max"))), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;
            case "minecraft-commands":
                try {
                    $action = json_encode(array("type" => "minecraft-commands",
                        "commands" => filter_input(INPUT_POST, "minecraft-commands"),
                        "servers" => $_POST['minecraft-servers']), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;

            case "none": //Error, redirect
                Flash::send(Alert::ERROR, LangManager::translate("core.toaster.error"),
                    LangManager::translate("core.toaster.internalError"));
                Redirect::redirectPreviousRoute();
                break;
        }

        //Add reward
        VotesRewardsModel::getInstance()->addReward($title, $action);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.add.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/rewards/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteRewardPostAdmin(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.delete");

        $title = VotesRewardsModel::getInstance()->getRewardById($id)?->getTitle();

        VotesRewardsModel::getInstance()->deleteReward($id);


        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.delete.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    #[Link("/rewards", Link::POST, [], "/cmw-admin/votes")]
    public function editRewardPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.edit");

        [$rewardsId, $rewardType, $title] = Utils::filterInput("reward_id", "reward_type", "title");

        $action = "";
        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                try {
                    $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;

            case "votepoints-random":
                try {
                    $action = json_encode(array("type" => "votepoints-random",
                        "amount" => array(
                            "min" => filter_input(INPUT_POST, "amount-min"),
                            "max" => filter_input(INPUT_POST, "amount-max"))), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;
            case "minecraft-commands":
                try {
                    $action = json_encode(array("type" => "minecraft-commands",
                        "commands" => filter_input(INPUT_POST, "minecraft-commands"),
                        "servers" => $_POST['minecraft-servers']), JSON_THROW_ON_ERROR);
                } catch (JsonException) {
                }
                break;

            case "none": //Error, redirect
                Flash::send(Alert::ERROR, LangManager::translate("core.toaster.error"),
                    LangManager::translate("core.toaster.internalError", ["name" => $title]));
                Redirect::redirectPreviousRoute();
                break;
        }

       VotesRewardsModel::getInstance()->updateReward($rewardsId, $title, $action);

        Flash::send(Alert::SUCCESS, LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.edit.success", ["name" => $title]));

        Redirect::redirectPreviousRoute();
    }

    //Return the reward with a specific ID
    #[Link("/rewards/get", Link::POST, [], "/cmw-admin/votes", secure: false)]
    public function getReward(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.edit");

        /* Error section */
        if (empty(filter_input(INPUT_POST, "id"))) {
            try {
                echo json_encode(array("response" => "ERROR-EMPTY_ID"), JSON_THROW_ON_ERROR);
            } catch (JsonException) {
            }
        } else {
            echo VotesRewardsModel::getInstance()->getRewardById(filter_input(INPUT_POST, "id"))?->getAction();
        }

    }

    /* ///////////////////// STATS /////////////////////*/

    #[Link("/stats", Link::GET, [], "/cmw-admin/votes")]
    public function statsVotes(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.stats");

        $stats = VotesStatsModel::getInstance();

        $all = $stats->statsVotes("all");
        $month = $stats->statsVotes("month");
        $week = $stats->statsVotes("week");
        $day = $stats->statsVotes("day");

        $listSites = VotesSitesModel::getInstance()->getSites();

        $actualTop = $stats->getActualTopNoLimit();
        $globalTop = $stats->getGlobalTopNoLimit();
        $previousTop = $stats->getPreviousMonthTop();

        $previous3Months = $stats->get3PreviousMonthsVotes();

        View::createAdminView('Votes', 'stats')
            ->addScriptBefore("Admin/Resources/Vendors/Chart/chart.min.js",
                "App/Package/Votes/Views/Resources/Js/main.js")
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css",
                "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js",
                "Admin/Resources/Assets/Js/Pages/simple-datatables.js")
            ->addVariableList(["stats" => $stats, "all" => $all, "month" => $month, "week" => $week, "day" => $day,
                "listSites" => $listSites, "actualTop" => $actualTop,
                "globalTop" => $globalTop, "previousTop" => $previousTop, "previous3Months" => $previous3Months])
            ->view();
    }



    /* //////////////////////////////////////////////////////////////*/
    /* ///////////////////// PUBLIC VOTE SECTION ////////////////////*/
    /* //////////////////////////////////////////////////////////////*/


    #[Link('/vote', Link::GET)]
    public function votesPublic(): void
    {
        $sites = VotesSitesModel::getInstance()->getSites();

        $topCurrent = VotesStatsModel::getInstance()->getActualTop();
        $topGlobal = VotesStatsModel::getInstance()->getGlobalTop();

        //Include the Public view file ("Public/Themes/$themePath/Views/Votes/main.view.php")
        $view = new View('Votes', 'main');

        $view->addVariableList(["sites" => $sites,
            "topCurrent" => $topCurrent, "topGlobal" => $topGlobal]);
        $view->addScriptAfter("App/Package/Votes/Views/Resources/Js/public.js");
        $view->view();
    }

    #[Link('/vote/send/:id', Link::GET, ["id" => "[0-9]+"])]
    public function votesWebsitePublic(Request $request, int $id): void
    {
        try {
            //First, check if the player can vote.
            if (CheckVotesModel::getInstance()->isVoteSend(VotesSitesModel::getInstance()->getSiteById($id)?->getUrl(),
                VotesSitesModel::getInstance()->getSiteById($id)?->getIdUnique(), Website::getClientIp())) {

                //Check if the player has a vote stored
                if (VotesModel::getInstance()->playerHasAVoteStored(UsersModel::getCurrentUser()?->getId(), $id)) {

                    //Check if we can validate this vote
                    if (VotesModel::getInstance()->validateThisVote(UsersModel::getCurrentUser()?->getId(), $id)) {
                        VotesModel::getInstance()->storeVote(UsersModel::getCurrentUser()?->getId(), $id);
                        VotesRewardsModel::getInstance()->selectReward(UsersModel::getCurrentUser()?->getId(), $id);

                        if (VotesConfigModel::getInstance()->getConfig()?->isEnableApi() &&
                            json_decode(VotesRewardsModel::getInstance()->getRewardById($id)?->getAction(), false, 512,
                                JSON_THROW_ON_ERROR)->type === "minecraft-commands") {
                            $this->sendRewardsToCmwLink($id);
                            // TODO config to toggle this feature
                            $this->sendVoteToCmwLink($id, VotesSitesModel::getInstance()->getSiteById($id)?->getTitle());
                        }

                        $this->returnData("send", true);
                    } else {
                        $this->returnData("already_vote", true);
                    }

                } else { //The player don't have any vote for this website.
                    VotesModel::getInstance()->storeVote(UsersModel::getCurrentUser()?->getId(), $id);
                    VotesRewardsModel::getInstance()->selectReward(UsersModel::getCurrentUser()?->getId(), $id);

//                    if (VotesConfigModel::getInstance()->getConfig()?->isEnableApi() &&
//                        json_decode(VotesRewardsModel::getInstance()->getRewardById($id)?->getAction(), false, 512,
//                            JSON_THROW_ON_ERROR)->type === "minecraft-commands") {
//                        $this->sendRewardsToCmwLink($id);
//                    }

                    $this->returnData("send", true);
                }

            } else {// The player has already voted.
                $this->returnData("not_send");
            }
        } catch (JsonException $e) {
            echo "Internal Error. " . $e;
        }
    }

    public function sendRewardsToCmwLink(int $rewardId): void
    {
        try {
            foreach (json_decode(VotesRewardsModel::getInstance()->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                // TODO Check if package is installed
                $server = (new MinecraftModel())->getServerById($serverId);
                $currentUser = UsersModel::getCurrentUser()?->getPseudo();

                $cmd = json_decode(VotesRewardsModel::getInstance()->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->commands;
                $cmd = str_replace("{player}", $currentUser, $cmd);
                $cmd = base64_encode($cmd);

                echo APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/reward/$currentUser/$cmd",
                    cmwlToken: $server?->getServerCMWToken());
            }
        } catch (JsonException $e) {
            echo "Internal Error. " . $e;
        }

    }

    public function sendVoteToCmwLink(int $rewardId, string $siteName): void
    {
        try {
            foreach (json_decode(VotesRewardsModel::getInstance()->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $rewardName = base64_encode(VotesRewardsModel::getInstance()->getRewardById($rewardId)?->getTitle());
                $siteName = base64_encode($siteName);

                // TODO Check if package is installed
                $server = (new MinecraftModel())->getServerById($serverId);
                $currentUser = UsersModel::getCurrentUser()?->getPseudo();

                echo APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/validate/$currentUser/$siteName/$rewardName",
                    cmwlToken: $server?->getServerCMWToken());
            }
        } catch (JsonException $e) {
            echo "Internal Error. " . $e;
        }

    }

    private function returnData(string $toReturn, bool $isFinal = false): void
    {
        try {
            print(json_encode($toReturn, JSON_THROW_ON_ERROR));

            if ($isFinal) {
                die();
            }
        } catch (JsonException $e) {
            echo "Can't return data. " . $e;
        }
    }

    #[Link('/vote/geturl/:id', Link::GET, ["id" => "[0-9]+"])]
    public function votesGetWebsiteUrlPublic(Request $request, int $id): void
    {
        print VotesSitesModel::getInstance()->getSiteById($id)?->getUrl();
    }

}