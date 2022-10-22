<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Users\UsersController;
use CMW\Manager\Api\APIManager;
use CMW\Model\Minecraft\MinecraftModel;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\ConfigModel;
use CMW\Model\Votes\RewardsModel;
use CMW\Model\Votes\SitesModel;
use CMW\Model\Votes\StatsModel;
use CMW\Model\Votes\VotesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\View;
use JsonException;


/**
 * Class: @VotesController
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesController extends CoreController
{

    public static string $themePath;
    private ConfigModel $configModel;
    private RewardsModel $rewardsModel;
    private SitesModel $sitesModel;
    private StatsModel $statsModel;
    private VotesModel $votesModel;


    public function __construct($themePath = null,)
    {
        parent::__construct($themePath);
        $this->configModel = new ConfigModel();
        $this->rewardsModel = new RewardsModel();
        $this->sitesModel = new SitesModel();
        $this->statsModel = new StatsModel();
        $this->votesModel = new VotesModel();
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

        $topShow = filter_input(INPUT_POST, 'topShow');
        $reset = filter_input(INPUT_POST, 'reset');
        $autoTopRewardActive = filter_input(INPUT_POST, 'autoTopRewardActive');
        $autoTopReward = filter_input(INPUT_POST, 'autoTopReward');
        $enableApi = filter_input(INPUT_POST, 'api');


        //Faire la config pour les rewards


        $this->configModel->updateConfig($topShow, $reset, $autoTopRewardActive, $autoTopReward, $enableApi);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../votes/config/');
    }


    /* ///////////////////// SITES /////////////////////*/

    #[Link("/site/add", Link::GET, [], "/cmw-admin/votes")]
    public function addSiteAdmin(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        $rewards = $this->rewardsModel->getRewards();

        View::createAdminView('votes', 'add_site')
            ->addVariableList(["rewards" => $rewards])
            ->addScriptBefore("app/package/votes/views/resources/js/testSitesId.js", "admin/resources/vendors/sweetalert2/sweetalert2.all.js")
            ->addStyle("admin/resources/vendors/sweetalert2/sweetalert2.css")
            ->view();
    }

    #[Link("/site/add", Link::POST, [], "/cmw-admin/votes")]
    public function addSiteAdminPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.add");

        $title = filter_input(INPUT_POST, 'title');
        $time = filter_input(INPUT_POST, 'time');
        $idUnique = filter_input(INPUT_POST, 'idUnique');
        $url = filter_input(INPUT_POST, 'url');
        $rewardsId = filter_input(INPUT_POST, 'reward');

        $this->sitesModel->addSite($title, $time, $idUnique, $url, $rewardsId);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header('location: ../site/add');
    }


    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function listSites(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.list");

        $sites = $this->sitesModel->getSites();
        $rewards = $this->rewardsModel->getRewards();

        View::createAdminView('votes', 'list_sites')
            ->addVariableList(["sites" => $sites, "rewards" => $rewards])
            ->addScriptAfter("admin/resources/vendors/sweetalert2/sweetalert2.all.js", "app/package/votes/views/resources/js/testSitesId.js")
            ->addStyle("admin/resources/vendors/sweetalert2/sweetalert2.css")
            ->view();
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

    #[Link("/site/list", Link::POST, [], "/cmw-admin/votes")]
    public function votesSitesEditPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.edit");

        $siteId = filter_input(INPUT_POST, 'siteId');
        $title = filter_input(INPUT_POST, 'title');
        $time = filter_input(INPUT_POST, 'time');
        $idUnique = filter_input(INPUT_POST, 'idUnique');
        $url = filter_input(INPUT_POST, 'url');
        $rewardsId = filter_input(INPUT_POST, 'reward');
        $this->sitesModel->updateSite($siteId, $title, $time, $idUnique, $url, $rewardsId);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../site/list/');
    }

    #[Link("/site/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteSitePostAdmin(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.site.delete");

        $this->sitesModel->deleteSite($id);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../list/');
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
            ->addScriptBefore("app/package/votes/views/resources/js/reward.js")
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
                $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")), JSON_THROW_ON_ERROR);
                break;

            case "votepoints-random":
                $action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))), JSON_THROW_ON_ERROR);
                break;
            case "minecraft-commands":
                $action = json_encode(array("type" => "minecraft-commands",
                    "commands" => filter_input(INPUT_POST, "minecraft-commands"),
                    "servers" => $_POST['minecraft-servers']), JSON_THROW_ON_ERROR);
                break;

            case "none"://Error, redirect
//                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
//                $_SESSION['toaster'][0]['type'] = "bg-danger";
//                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../rewards");
                break;
        }

        //Add reward
        $this->rewardsModel->addReward($title, $action);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header("location: ../rewards");
    }

    #[Link("/rewards/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteRewardPostAdmin(int $id): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.delete");

        $this->rewardsModel->deleteReward($id);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../../rewards');
    }

    #[Link("/rewards", Link::POST, [], "/cmw-admin/votes")]
    public function editRewardPost(): void
    {
        UsersController::redirectIfNotHavePermissions("core.dashboard", "votes.rewards.edit");

        $rewardsId = filter_input(INPUT_POST, "reward_id");
        $rewardType = filter_input(INPUT_POST, "reward_type");
        $title = filter_input(INPUT_POST, "title");

        $action = "";
        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")), JSON_THROW_ON_ERROR);
                break;

            case "votepoints-random":
                $action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))), JSON_THROW_ON_ERROR);
                break;
            case "minecraft-commands":
                $action = json_encode(array("type" => "minecraft-commands",
                    "commands" => filter_input(INPUT_POST, "minecraft-commands"),
                    "servers" => $_POST['minecraft-servers']), JSON_THROW_ON_ERROR);
                break;

            case "none"://Error, redirect
//                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
//                $_SESSION['toaster'][0]['type'] = "bg-danger";
//                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../votes/rewards");
                break;
        }


        $this->rewardsModel->updateReward($rewardsId, $title, $action);

//        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
//        $_SESSION['toaster'][0]['type'] = "bg-success";
//        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: rewards');
    }

    //Return the reward with a specific ID
    #[Link("/rewards/get", Link::POST, [], "/cmw-admin/votes", secure: false)]
    public function getReward(): void
    {
        /* Error section */
        if (empty(filter_input(INPUT_POST, "id"))) {
            try {
                echo json_encode(array("response" => "ERROR-EMPTY_ID"), JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
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

        //TODO REWORK THIS PART
        //Index -> Entities and more...

        $stats = new statsModel();

        $all = $stats->statsVotes("all");
        $month = $stats->statsVotes("month");
        $week = $stats->statsVotes("week");
        $day = $stats->statsVotes("day");

        $listSites = $this->sitesModel->getSites();

        $numberOfSites = $stats->getNumberOfSites();

        $actualTop = $stats->getActualTopNoLimit();
        $globalTop = $stats->getGlobalTopNoLimit();
        $previousTop = $stats->getPreviousMonthTop();


        View::createAdminView('votes', 'stats')
            ->addScriptBefore("admin/resources/vendors/bootstrap/js/bootstrap.bundle.min.js",
                "admin/resources/vendors/datatables/jquery.dataTables.min.js",
                "admin/resources/vendors/datatables-bs4/js/dataTables.bootstrap4.min.js",
                "admin/resources/vendors/datatables-responsive/js/dataTables.responsive.min.js",
                "admin/resources/vendors/datatables-responsive/js/responsive.bootstrap4.min.js",
                "admin/resources/vendors/chart.js/Chart.min.js",
                "app/package/votes/views/resources/js/main.js")
            ->addStyle("admin/resources/vendors/datatables-bs4/css/dataTables.bootstrap4.min.css",
                "admin/resources/vendors/datatables-responsive/css/responsive.bootstrap4.min.css")
            ->addVariableList(["stats" => $stats, "all" => $all, "month" => $month, "week" => $week, "day" => $day,
                "listSites" => $listSites, "numberOfSites" => $numberOfSites, "actualTop" => $actualTop,
                "globalTop" => $globalTop, "previousTop" => $previousTop])
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

        //Include the public view file ("public/themes/$themePath/views/votes/main.view.php")
        $view = new View('votes', 'main');

        $view->addVariableList(["sites" => $sites,
            "topCurrent" => $topCurrent, "topGlobal" => $topGlobal]);
        $view->addScriptAfter("app/package/votes/views/resources/js/public.js");
        $view->view();
    }

    #[Link('/vote/send/:id', Link::GET, ["id" => "[0-9]+"])]
    public function votesWebsitePublic(int $id): void
    {
        try {
            //First, check if the player can vote.
            if ($this->votesModel->isVoteSend($this->sitesModel->getSiteById($id)?->getUrl(), $this->sitesModel->getSiteById($id)?->getIdUnique(), Utils::getClientIp())) {

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
            echo $e;
        }
    }

    public function sendRewardsToCmwLink(string $rewardId): void
    {
        foreach (json_decode($this->rewardsModel->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->servers as $serverId) {
            $server = (new MinecraftModel())->getServerById($serverId);
            $currentUser = UsersModel::getCurrentUser()?->getUsername();
            $cmd = base64_encode(json_decode($this->rewardsModel->getRewardById($rewardId)?->getAction(), false, 512, JSON_THROW_ON_ERROR)->commands);
            echo APIManager::getRequest("http://{$server?->getServerIp()}:{$server?->getServerCMWLPort()}/votes/send/reward/$currentUser/$cmd");
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
        }
    }

    #[Link('/vote/geturl/:id', Link::GET, ["id" => "[0-9]+"])]
    public function votesGetWebsiteUrlPublic(int $id): void
    {
        print $this->sitesModel->getSiteById($id)?->getUrl();
    }

}