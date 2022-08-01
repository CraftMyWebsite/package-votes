<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Controller\Menus\menusController;
use CMW\Controller\users\usersController;
use CMW\Model\users\usersModel;
use CMW\Model\votes\configModel;
use CMW\Model\votes\RewardsModel;
use CMW\Model\votes\sitesModel;
use CMW\Model\Votes\statsModel;
use CMW\Model\Votes\VotesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
use CMW\Utils\View;


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
        $this->configModel = new configModel();
        $this->rewardsModel = new RewardsModel();
        $this->sitesModel = new sitesModel();
        $this->statsModel = new statsModel();
        $this->votesModel = new VotesModel();
    }

    /* ///////////////////// CONFIG /////////////////////*/

    #[Link(path: "/", method: Link::GET, scope: "/cmw-admin/votes")]
    #[Link("/config", Link::GET, [], "/cmw-admin/votes")]
    public function votesConfig()
    {
        $config = $this->configModel->getConfig();

        View::createAdminView('votes', 'config')
            ->addVariableList(["config" => $config])
            ->view();
    }

    #[Link("/config", Link::POST, [], "/cmw-admin/votes")]
    public function votesConfigPost()
    {
        //Keep this perm control just for the post function
        usersController::isAdminLogged();

        $topShow = filter_input(INPUT_POST, 'topShow');
        $reset = filter_input(INPUT_POST, 'reset');
        $autoTopRewardActive = filter_input(INPUT_POST, 'autoTopRewardActive');
        $autoTopReward = filter_input(INPUT_POST, 'autoTopReward');


        //Faire la config pour les rewards


        $this->configModel->updateConfig($topShow, $reset, $autoTopRewardActive, $autoTopReward);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../votes/config/');
    }


    /* ///////////////////// SITES /////////////////////*/

    #[Link("/site/add", Link::GET, [], "/cmw-admin/votes")]
    public function addSiteAdmin()
    {

        $rewards = $this->rewardsModel->getRewards();


        View::createAdminView('votes', 'add_site')
            ->addVariableList(["rewards" => $rewards])
            ->addScriptBefore("app/package/votes/views/resources/js/testSitesId.js", "admin/resources/vendors/sweetalert2/sweetalert2.all.js")
            ->addStyle("admin/resources/vendors/sweetalert2/sweetalert2.css")
            ->view();
    }

    #[Link("/site/add", Link::POST, [], "/cmw-admin/votes")]
    public function addSiteAdminPost()
    {
        usersController::isAdminLogged();


        $title = filter_input(INPUT_POST, 'title');
        $time = filter_input(INPUT_POST, 'time');
        $idUnique = filter_input(INPUT_POST, 'idUnique');
        $url = filter_input(INPUT_POST, 'url');
        $rewardsId = filter_input(INPUT_POST, 'reward');

        $this->sitesModel->addSite($title, $time, $idUnique, $url, $rewardsId);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header('location: ../site/add');
    }


    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function listSites()
    {
        $sites = $this->sitesModel->getSites();
        $rewards = $this->rewardsModel->getRewards();

        View::createAdminView('votes', 'list_sites')
            ->addVariableList(["sites" => $sites, "rewards" => $rewards])
            ->addScriptBefore("app/package/votes/views/resources/js/testSitesId.js", "admin/resources/vendors/sweetalert2/sweetalert2.all.js")
            ->addStyle("admin/resources/vendors/sweetalert2/sweetalert2.css")
            ->view();
    }

    #[Link("/site/list", Link::GET, [], "/cmw-admin/votes")]
    public function votesSitesEdit()
    {
        $votes = $this->sitesModel->getSiteById(filter_input(INPUT_POST, 'siteId'));

        View::createAdminView('votes', 'list_sites')
            ->addVariableList(["votes" => $votes])
            ->view();
    }

    #[Link("/site/list", Link::POST, [], "/cmw-admin/votes")]
    public function votesSitesEditPost()
    {
        usersController::isAdminLogged();

        $siteId = filter_input(INPUT_POST, 'siteId');
        $title = filter_input(INPUT_POST, 'title');
        $time = filter_input(INPUT_POST, 'time');
        $idUnique = filter_input(INPUT_POST, 'idUnique');
        $url = filter_input(INPUT_POST, 'url');
        $rewardsId = filter_input(INPUT_POST, 'reward');
        $this->sitesModel->updateSite($siteId, $title, $time, $idUnique, $url, $rewardsId);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: ../site/list/');
    }

    #[Link("/site/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteSitePostAdmin(int $id)
    {
        usersController::isAdminLogged();

        $this->sitesModel->deleteSite($id);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../list/');
    }


    /* ///////////////////// REWARDS /////////////////////*/

    #[Link("/rewards", Link::GET, [], "/cmw-admin/votes")]
    public function votesRewards()
    {

        $rewards = $this->rewardsModel->getRewards();

        View::createAdminView('votes', 'rewards')
            ->addVariableList(["rewards" => $rewards])
            ->addScriptBefore("app/package/votes/views/resources/js/reward.js")
            ->view();
    }

    #[Link("/rewards/add", Link::POST, [], "/cmw-admin/votes")]
    public function addRewardPost()
    {
        usersController::isAdminLogged();


        $rewardType = filter_input(INPUT_POST, "reward_type");
        $title = filter_input(INPUT_POST, "title");

        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")));
                break;

            case "votepoints-random":
                $action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))));
                break;

            case "none"://Error, redirect
                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
                $_SESSION['toaster'][0]['type'] = "bg-danger";
                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../rewards");
                break;
        }

        //Add reward
        $this->rewardsModel->addReward($title, $action);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ADD_SUCCESS;

        header("location: ../rewards");
    }

    #[Link("/rewards/delete/:id", Link::GET, ['id' => '[0-9]+'], "/cmw-admin/votes")]
    public function deleteRewardPostAdmin(int $id)
    {
        usersController::isAdminLogged();

        $this->rewardsModel->deleteReward($id);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_DELETE_SUCCESS;

        header('location: ../rewards');
    }

    #[Link("/rewards", Link::POST, [], "/cmw-admin/votes")]
    public function editRewardPost()
    {
        usersController::isAdminLogged();


        $rewardsId = filter_input(INPUT_POST, "reward_id");
        $rewardType = filter_input(INPUT_POST, "reward_type");
        $title = filter_input(INPUT_POST, "title");


        //Define the reward action
        switch ($rewardType) {
            case "votepoints":
                $action = json_encode(array("type" => "votepoints", "amount" => filter_input(INPUT_POST, "amount")));
                break;

            case "votepoints-random":
                $action = json_encode(array("type" => "votepoints-random",
                    "amount" => array(
                        "min" => filter_input(INPUT_POST, "amount-min"),
                        "max" => filter_input(INPUT_POST, "amount-max"))));
                break;

            case "none"://Error, redirect
                $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_ERROR;
                $_SESSION['toaster'][0]['type'] = "bg-danger";
                $_SESSION['toaster'][0]['body'] = VOTES_TOAST_ERROR_INTERNAL;
                header("location: ../votes/rewards");
                break;
        }


        $this->rewardsModel->updateReward($rewardsId, $title, $action);

        $_SESSION['toaster'][0]['title'] = VOTES_TOAST_TITLE_SUCCESS;
        $_SESSION['toaster'][0]['type'] = "bg-success";
        $_SESSION['toaster'][0]['body'] = VOTES_TOAST_EDIT_SUCCESS;

        header('location: rewards');
    }

    //Return the reward with a specific ID
    public function getReward()
    {
        /* Error section */
        if (empty(filter_input(INPUT_POST, "id"))) {
            echo json_encode(array("response" => "ERROR-EMPTY_ID"));
        } else {
            echo $this->rewardsModel->getRwardById(filter_input(INPUT_POST, "id"))->getAction();
        }

    }

    /* ///////////////////// STATS /////////////////////*/

    #[Link("/stats", Link::GET, [], "/cmw-admin/votes")]
    public function statsVotes()
    {

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
            ->addVariableList(["stats" => $stats, "all" => $all, "month" => $month, "week" => $week, "day" => $day,
                "listSites" => $listSites, "numberOfSites" => $numberOfSites, "actualTop" => $actualTop,
                "globalTop" => $globalTop, "previousTop" => $previousTop])
            ->view();
    }



    /* //////////////////////////////////////////////////////////////*/
    /* ///////////////////// PUBLIC VOTE SECTION ////////////////////*/
    /* //////////////////////////////////////////////////////////////*/

    /**
     * @throws \CMW\Router\RouterException
     */
    #[Link('/vote', Link::GET)]
    public function votesPublic()
    {
        $vote = new VotesModel();


        $sites = $this->sitesModel->getSites();

        $_SESSION['votes']['token'] = Utils::genId(15);


        $topCurrent = $this->statsModel->getActualTop();
        $topGlobal = $this->statsModel->getGlobalTop();

        //Include the public view file ("public/themes/$themePath/views/votes/main.view.php")
        $view = new View('votes', 'main');

        $view->addVariableList(["votes" => $vote, "sites" => $sites,
            "topCurrent" => $topCurrent, "topGlobal" => $topGlobal]);
        $view->view();


    }

    #[Link('/vote/verify', Link::POST)]
    public function votesPublicVerify()
    {

        /* Error section */
        if (empty(filter_input(INPUT_POST, "url"))) {
            echo json_encode(array("response" => "ERROR-URL"));
        } else {


            $vote = new VotesModel();
            $user = new usersModel();
            $reward = new RewardsModel();


            $url = filter_input(INPUT_POST, "url");

            $site = $vote->getSite($url);
            $vote->idUnique = $site['id_unique'];
            $vote->idSite = $site['id'];

            $reward->idSite = $vote->idSite;

            $vote->getClientIp();

            //Get user id
            $userId = (new UsersModel())->getUserById($_SESSION['cmsUserId']);


            if ($vote->check($url) === true) {

                if ($vote->hasVoted() === "NEW_VOTE") {

                    //Store the vote
                    $vote->storeVote();

                    //Get reward
                    $reward->selectReward();

                    echo json_encode(array("response" => "GOOD-NEW_VOTE"));
                } else {
                    //If the player can get the reward
                    if ($vote->hasVoted() === "GOOD") {
                        //Store the vote
                        $vote->storeVote();

                        //Get reward
                        $reward->selectReward();

                        echo json_encode(array("response" => "GOOD"));

                        //If the player has already vote
                    } else if ($vote->hasVoted() === "ALREADY_VOTE") {

                        echo json_encode(array("response" => "ALREADY_VOTE"));

                    } else {
                        echo json_encode(array("response" => $vote->hasVoted()));
                    }
                }

            } else {//retry
                echo json_encode(array("response" => "NOT_CONFIRMED"));
            }

        }

    }


}