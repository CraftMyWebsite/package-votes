<?php

namespace CMW\Model\Votes;

use CMW\Model\Manager;


/**
 * Class @statsModel
 * @package votes
 * @author Teyir | CraftMySite <contact@craftmysite.fr>
 * @version 1.0
 */
class StatsModel extends Manager
{

    public int $votePoints;
    public string $pseudo;
    public int $votesTotaux;
    public int $votesCurrent;


    /**
     * @param string $type insert the type you want: "all", "month", "week", "day", "hour", "minute"
     * @return void
     */
    public function statsVotes($type): array
    {

        if ($type === "all") {

            $sql = "SELECT * FROM cmw_votes_votes";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $res = $req->execute();

        } else if ($type === "month" || $type === "week" || $type === "day" || $type === "hour" || $type === "minute") {

            if ($type === "month") {
                $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));
            }

            switch ($type) {
                case "month":
                    $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                    $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));
                    break;
                case "week":
                    $rangeStart = date("Y-m-d 00:00:00", strtotime("monday this week"));
                    $rangeFinish = date("Y-m-d 00:00:00", strtotime("sunday this week"));
                    break;
                case "day":
                    $rangeStart = date("Y-m-d 00:00:00");
                    $rangeFinish = date("Y-m-d 23:59:59");
                    break;
                case "hour":
                    $rangeStart = date("Y-m-d h:00:00");
                    $rangeFinish = date("Y-m-d h:00:00", strtotime("+1 hour"));
                    break;
                case "minute":
                    $rangeStart = date("Y-m-d h:i:00");
                    $rangeFinish = date("Y-m-d h:i:00", strtotime("+1 minute"));
                    break;
            }


            $var = array(
                "range_start" => $rangeStart,
                "range_finish" => $rangeFinish
            );

            $sql = "SELECT * FROM cmw_votes_votes WHERE votes_date BETWEEN (:range_start) AND (:range_finish)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $res = $req->execute($var);
        }

        if ($res) {
            return $req->fetchAll();
        }

        return [];

    }

    public function statsVotesSitesTotaux($title): int
    {

        $var = array(
            "title" => $title
        );

        $sql = 'SELECT cmw_votes_votes.votes_id, cmw_votes_sites.votes_sites_title FROM cmw_votes_votes 
                    JOIN cmw_votes_sites ON cmw_votes_votes.votes_id_site = cmw_votes_sites.votes_sites_id 
                                                                   WHERE cmw_votes_sites.votes_sites_title = :title;';

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";

    }

    public function statsVotesSitesMonth($title): int
    {

        $rangeStart = date("Y-m-d 00:00:00", strtotime("first day of this month"));
        $rangeFinish = date("Y-m-d 00:00:00", strtotime("last day of this month"));

        $var = array(
            "title" => $title,
            "range_start" => $rangeStart,
            "range_finish" => $rangeFinish
        );

        $sql = 'SELECT cmw_votes_votes.votes_id, cmw_votes_sites.votes_sites_title FROM cmw_votes_votes 
                    JOIN cmw_votes_sites ON cmw_votes_votes.votes_id_site = cmw_votes_sites.votes_sites_id 
                    WHERE cmw_votes_sites.votes_sites_title = :title
                    AND cmw_votes_votes.votes_date BETWEEN (:range_start) AND (:range_finish);';

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";

    }

    public function getNumberOfSites(): int
    {
        $sql = "SELECT votes_sites_id FROM cmw_votes_sites";
        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return "";
    }

    //Public function for the top votes (actual month)

    /**
     * @return array (votes, pseudo)
     */
    public function getActualTop(): array
    {

        $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC LIMIT 10";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }

    //Public function for the top votes (global)

    /**
     * @return array (votes, pseudo)
     */
    public function getGlobalTop(): array
    {

        $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC LIMIT 10";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }


    public function getActualTopNoLimit(): array
    {

        $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }

    public function getGlobalTopNoLimit(): array
    {

        $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }

    public function getPreviousMonthTop(): array
    {

        $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute()) {
            return $req->fetchAll();
        }

        return [];
    }


}