<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Controller\Users\UsersController;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Views\View;
use CMW\Model\Votes\VotesSitesModel;
use CMW\Model\Votes\VotesStatsModel;

/**
 * Class: @VotesStatsController
 * @package Votes
 * @author Teyir & Zomb
 * @version 1.0
 */
class VotesStatsController extends AbstractController
{
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
}