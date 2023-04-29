<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Requests\Request;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\CheckVotesModel;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Model\Votes\VotesModel;
use CMW\Model\Votes\VotesRewardsModel;
use CMW\Model\Votes\VotesSitesModel;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Router\Link;
use CMW\Utils\Response;
use CMW\Utils\Utils;
use CMW\Manager\Views\View;
use JsonException;


/**
 * Class: @VotesController
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesController extends CoreController
{

    private VotesConfigModel $configModel;
    private VotesRewardsModel $rewardsModel;
    private VotesSitesModel $sitesModel;
    private VotesStatsModel $statsModel;
    private VotesModel $votesModel;
    private CheckVotesModel $checkVotesModel;


    public function __construct()
    {
        parent::__construct();
        $this->configModel = new VotesConfigModel();
        $this->rewardsModel = new VotesRewardsModel();
        $this->sitesModel = new VotesSitesModel();
        $this->statsModel = new VotesStatsModel();
        $this->votesModel = new VotesModel();
        $this->checkVotesModel = new CheckVotesModel();
    }

    /* ///////////////////// CONFIG /////////////////////*/

    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/votes")]
    #[Link("/config", Link::GET, [], "/cmw-admin/votes")]
    public function votesConfig(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.configuration");

        $config = $this->configModel->getConfig();

        View::createAdminView('votes', 'config')
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/config", Link::POST, [], "/cmw-admin/votes")]
    public function votesConfigPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.configuration");

        [$topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi] = Utils::filterInput("topShow",
            "reset", "autoTopRewardActive", "autoTopReward", "api");


        $this->configModel->updateConfig($topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("core.toaster.config.success"));

        header('location: ../votes/config/');
    }


    /* ///////////////////// SITES /////////////////////*/

    public function getCompatiblesSites(): array
    {
        $file = Utils::getEnv()->getValue("DIR") . "App/Package/votes/minecraftSitesCompatibles.php";

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

        $sites = $this->sitesModel->getSites();
        $rewards = $this->rewardsModel->getRewards();
        $compatiblesSites = $this->getCompatiblesSites();

        View::createAdminView('votes', 'list_sites')
            ->addVariableList(["sites" => $sites, "rewards" => $rewards, "compatiblesSites" => $compatiblesSites])
            ->addStyle("App/Package/votes/Views/Resources/Vendors/Css/iziToast.min.css",
                "Admin/Resources/Vendors/Simple-datatables/style.css",
                "Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("App/Package/votes/Views/Resources/Vendors/Js/iziToast.min.js",
                "App/Package/votes/Views/Resources/Js/testSitesId.js",
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

        $this->sitesModel->addSite($title, $time, $idUnique, $url, $rewardsId);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.add.success", ["name" => $title]));

        header('location: ../site/list');
    }

    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function votesSitesEdit(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        $votes = $this->sitesModel->getSiteById(filter_input(INPUT_POST, 'siteId'));

        View::createAdminView('votes', 'list_sites')
            ->addVariableList(["votes" => $votes])
            ->view();
    }

    #[Link("/site/edit", Link::POST, [], "/cmw-admin/votes")]
    public function votesSitesEditPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        [$siteId, $title, $time, $idUnique, $url, $rewardsId] = Utils::filterInput("siteId", "title", "time",
            "idUnique", "url", "reward");

        $this->sitesModel->updateSite($siteId, $title, $time, $idUnique, $url, $rewardsId);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.edit.success", ["name" => $title]));

        header('location: ../site/list/');
    }

    #[Link("/site/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteSitePostAdmin(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.delete");

        $title = $this->sitesModel->getSiteById($id)?->getTitle();

        $this->sitesModel->deleteSite($id);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.site.delete.success", ["name" => $title]));

        header('location: ../list/');
    }

    /**
     * @throws \JsonException
     */
    #[Link("/site/test/id", Link::POST, [], "/cmw-admin/votes", secure: false)]
    public function checkSiteId(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        [$url, $siteId] = Utils::filterInput('url', 'site_id');

        if (Utils::hasOneNullValue($url, $siteId)){
            print (json_encode(["status" => "0", "toaster" =>
                ["type" => "error",
                    "title" => LangManager::translate("core.toaster.error"),
                    "content" => LangManager::translate("votes.toaster.site.test_id.empty_input")
                ]
            ], JSON_THROW_ON_ERROR));
            return;
        }

        if ($this->checkVotesModel->testSiteId($url, $siteId)){
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

        $rewards = $this->rewardsModel->getRewards();

        $minecraftServers = (new MinecraftModel())->getServers();

        View::createAdminView('votes', 'rewards')
            ->addVariableList(["rewards" => $rewards, "minecraftServers" => $minecraftServers])
            ->addScriptBefore("App/Package/votes/Views/Resources/Js/reward.js")
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
                Response::sendAlert("error", LangManager::translate("core.toaster.error"),
                    LangManager::translate("core.toaster.internalError"));
                header("location: ../rewards");
                break;
        }

        //Add reward
        $this->rewardsModel->addReward($title, $action);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.add.success", ["name" => $title]));

        header("location: ../rewards");
    }

    #[Link("/rewards/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteRewardPostAdmin(Request $request, int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.delete");

        $title = $this->rewardsModel->getRewardById($id)?->getTitle();

        $this->rewardsModel->deleteReward($id);


        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.delete.success", ["name" => $title]));

        header('location: ../../rewards');
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
                Response::sendAlert("error", LangManager::translate("core.toaster.error"),
                    LangManager::translate("core.toaster.internalError", ["name" => $title]));
                header("location: ../votes/rewards");
                break;
        }

        $this->rewardsModel->updateReward($rewardsId, $title, $action);

        Response::sendAlert("success", LangManager::translate("core.toaster.success"),
            LangManager::translate("votes.toaster.reward.edit.success", ["name" => $title]));

        header('location: rewards');
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
            echo $this->rewardsModel->getRewardById(filter_input(INPUT_POST, "id"))?->getAction();
        }

    }

    /* ///////////////////// STATS /////////////////////*/

    #[Link("/stats", Link::GET, [], "/cmw-admin/votes")]
    public function statsVotes(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.stats");

        $stats = $this->statsModel;

        $all = $stats->statsVotes("all");
        $month = $stats->statsVotes("month");
        $week = $stats->statsVotes("week");
        $day = $stats->statsVotes("day");

        $listSites = $this->sitesModel->getSites();

        $actualTop = $stats->getActualTopNoLimit();
        $globalTop = $stats->getGlobalTopNoLimit();
        $previousTop = $stats->getPreviousMonthTop();

        $previous3Months = $stats->get3PreviousMonthsVotes();

        View::createAdminView('votes', 'stats')
            ->addScriptBefore("Admin/Resources/Vendors/chart/chart.min.js","App/Package/votes/Views/Resources/Js/main.js")
            ->addStyle("Admin/Resources/Vendors/Simple-datatables/style.css","Admin/Resources/Assets/Css/Pages/simple-datatables.css")
            ->addScriptAfter("Admin/Resources/Vendors/Simple-datatables/Umd/simple-datatables.js","Admin/Resources/Assets/Js/Pages/simple-datatables.js")
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
        $sites = $this->sitesModel->getSites();

        $topCurrent = $this->statsModel->getActualTop();
        $topGlobal = $this->statsModel->getGlobalTop();

        //Include the Public view file ("Public/Themes/$themePath/Views/votes/main.view.php")
        $view = new View('votes', 'main');

        $view->addVariableList(["sites" => $sites,
            "topCurrent" => $topCurrent, "topGlobal" => $topGlobal]);
        $view->addScriptAfter("App/Package/votes/Views/Resources/Js/Public.js");
        $view->view();
    }

    #[Link('/vote/send/:id', Link::GET, ["id" => "[0-9]+"])]
    public function votesWebsitePublic(Request $request, int $id): void
    {
        try {
            //First, check if the player can vote.
            if ($this->checkVotesModel->isVoteSend($this->sitesModel->getSiteById($id)?->getUrl(), $this->sitesModel->getSiteById($id)?->getIdUnique(), Utils::getClientIp())) {

                //Check if the player has a vote stored
                if ($this->votesModel->playerHasAVoteStored(UsersModel::getCurrentUser()?->getId(), $id)) {

                    //Check if we can validate this vote
                    if ($this->votesModel->validateThisVote(UsersModel::getCurrentUser()?->getId(), $id)) {
                        $this->votesModel->storeVote(UsersModel::getCurrentUser()?->getId(), $id);
                        $this->rewardsModel->selectReward(UsersModel::getCurrentUser()?->getId(), $id);

                        if ($this->configModel->getConfig()?->isEnableApi() &&
                            json_decode($this->rewardsModel->getRewardById($id)?->getAction(), false, 512,
                                JSON_THROW_ON_ERROR)->type === "minecraft-commands") {
                            $this->sendRewardsToCmwLink($id);
                            // TODO config to toggle this feature
                            $this->sendVoteToCmwLink($id, $this->sitesModel->getSiteById($id)?->getTitle());
                        }

                        $this->returnData("send", true);
                    } else {
                        $this->returnData("already_vote", true);
                    }

                } else { //The player don't have any vote for this website.
                    $this->votesModel->storeVote(UsersModel::getCurrentUser()?->getId(), $id);
                    $this->rewardsModel->selectReward(UsersModel::getCurrentUser()?->getId(), $id);

                    if ($this->configModel->getConfig()?->isEnableApi() &&
                        json_decode($this->rewardsModel->getRewardById($id)?->getAction(), false, 512,
                            JSON_THROW_ON_ERROR)->type === "minecraft-commands") {
                        $this->sendRewardsToCmwLink($id);
                    }

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
            foreach (json_decode($this->rewardsModel->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $server = (new MinecraftModel())->getServerById($serverId);
                $currentUser = UsersModel::getCurrentUser()?->getPseudo();

                $cmd = json_decode($this->rewardsModel->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->commands;
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
            foreach (json_decode($this->rewardsModel->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
                $rewardName = base64_encode($this->rewardsModel->getRewardById($rewardId)?->getTitle());
                $siteName = base64_encode($siteName);

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
        print $this->sitesModel->getSiteById($id)?->getUrl();
    }

}