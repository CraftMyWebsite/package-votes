<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesPlayerStatsEntity;
use CMW\Manager\Database\DatabaseManager;


/**
 * Class @VotesStatsModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesStatsModel extends DatabaseManager
{

    public int $votePoints;
    public string $pseudo;
    public int $votesTotaux;
    public int $votesCurrent;


    /**
     * @param string $type insert the type you want: "all", "month", "week", "day", "hour", "minute"
     * @return array
     */
    public function statsVotes(string $type): array
    {

        if ($type === "all") {

            $sql = "SELECT * FROM cmw_votes_votes";

            $db = self::getInstance();
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

            $db = self::getInstance();
            $req = $db->prepare($sql);
            $res = $req->execute($var);
        }

        if ($res) {
            return $req->fetchAll();
        }

        return [];

    }

    public function statsVotesSitesTotaux(string $title): int
    {

        $var = array(
            "title" => $title
        );

        $sql = 'SELECT cmw_votes_votes.votes_id, cmw_votes_sites.votes_sites_title FROM cmw_votes_votes 
                    JOIN cmw_votes_sites ON cmw_votes_votes.votes_id_site = cmw_votes_sites.votes_sites_id 
                                                                   WHERE cmw_votes_sites.votes_sites_title = :title;';

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return 0;

    }

    public function statsVotesSitesMonth(string $title): int
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

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute($var);

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return 0;

    }

    public function getNumberOfSites(): int
    {
        $sql = "SELECT votes_sites_id FROM cmw_votes_sites";
        $db = self::getInstance();
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
     * @return VotesPlayerStatsEntity[]
     */
    public function getActualTop(): array
    {

        if(self::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email  FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE())
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email  FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        }

        $sql .= "LIMIT " . (new VotesConfigModel())->getConfig()?->getTopShow();

        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                $stats['pseudo'],
                $stats['email']
            );
        }

        return $toReturn;
    }

    //Public function for the top votes (global)

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getGlobalTop(): array
    {

        if (self::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email  FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email  FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        }
        $sql .= "LIMIT " . (new VotesConfigModel())->getConfig()?->getTopShow();

        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                $stats['pseudo'],
                $stats['email']
            );
        }

        return $toReturn;
    }


    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getActualTopNoLimit(): array
    {

        if (self::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                as email FROM cmw_votes_votes
                JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user
                WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE())
                ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, ANY_VALUE(cmw_users.user_pseudo) as pseudo, ANY_VALUE(cmw_users.user_email) 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }

        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                $stats['pseudo'],
                $stats['email']
            );
        }

        return $toReturn;
    }

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getGlobalTopNoLimit(): array
    {

        if (self::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, ANY_VALUE(cmw_users.user_pseudo) as pseudo, ANY_VALUE(cmw_users.user_email) 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }

        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                $stats['pseudo'],
                $stats['email']
            );
        }

        return $toReturn;
    }

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getPreviousMonthTop(): array
    {

        if (self::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) as votes, cmw_users.user_pseudo as pseudo, cmw_users.user_email
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes, ANY_VALUE(cmw_users.user_pseudo) as pseudo, ANY_VALUE(cmw_users.user_email) 
                    as email FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }
        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                $stats['pseudo'],
                $stats['email']
            );
        }

        return $toReturn;
    }

    public function getPlayerVotepoints(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT votes_votepoints_amount as votepoints FROM cmw2.cmw_votes_votepoints 
                    WHERE votes_votepoints_id_user = :user LIMIT 1";
        } else {
            $sql = "SELECT cmw_votes_votepoints.votes_votepoints_amount as votepoints FROM cmw_votes_votepoints 
                    JOIN cmw_users ON cmw_votes_votepoints.votes_votepoints_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user LIMIT 1";
        }

        $db = self::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votepoints'] ?? 0;
        }

        return 0;
    }

    public function getPlayerCurrentVotes(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes FROM cmw_votes_votes 
                    WHERE cmw_votes_votes.votes_id_user = :user
                    AND MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) LIMIT 1";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_votes_votes.votes_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user
                    AND MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) LIMIT 1";
        }

        $db = self::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votes'] ?? 0;
        }

        return 0;
    }

    public function getPlayerTotalVotes(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes FROM cmw_votes_votes 
                    WHERE cmw_votes_votes.votes_id_user = :user LIMIT 1";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) as votes FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_votes_votes.votes_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user LIMIT 1";
        }

        $db = self::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votes'] ?? 0;
        }

        return 0;
    }

}