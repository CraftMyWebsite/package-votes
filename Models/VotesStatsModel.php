<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesPlayerStatsEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use CMW\Model\Users\UsersModel;


/**
 * Class @VotesStatsModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesStatsModel extends AbstractModel
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

        $rangeStart = null;
        $rangeFinish = null;

        if ($type === "all") {

            $sql = "SELECT * FROM cmw_votes_votes";

            $db = DatabaseManager::getInstance();
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


            $var = [
                "range_start" => $rangeStart,
                "range_finish" => $rangeFinish,
            ];

            $sql = "SELECT * FROM cmw_votes_votes WHERE votes_date BETWEEN (:range_start) AND (:range_finish)";

            $db = DatabaseManager::getInstance();
            $req = $db->prepare($sql);
            $res = $req->execute($var);

            if ($res) {
                return $req->fetchAll();
            }
        }

        return [];
    }

    public function statsVotesSitesTotaux(string $title): int
    {
        $var = [
            "title" => $title,
        ];

        $sql = 'SELECT cmw_votes_votes.votes_id, cmw_votes_sites.votes_sites_title FROM cmw_votes_votes 
                    JOIN cmw_votes_sites ON cmw_votes_votes.votes_id_site = cmw_votes_sites.votes_sites_id 
                                                                   WHERE cmw_votes_sites.votes_sites_title = :title;';

        $db = DatabaseManager::getInstance();
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

        $var = [
            "title" => $title,
            "range_start" => $rangeStart,
            "range_finish" => $rangeFinish,
        ];

        $sql = 'SELECT cmw_votes_votes.votes_id, cmw_votes_sites.votes_sites_title FROM cmw_votes_votes 
                    JOIN cmw_votes_sites ON cmw_votes_votes.votes_id_site = cmw_votes_sites.votes_sites_id 
                    WHERE cmw_votes_sites.votes_sites_title = :title
                    AND cmw_votes_votes.votes_date BETWEEN (:range_start) AND (:range_finish);';

        $db = DatabaseManager::getInstance();
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
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $res = $req->execute();

        if ($res) {
            $lines = $req->fetchAll();

            return count($lines);
        }

        return 0;
    }


    /**
     * @return VotesPlayerStatsEntity[]
     * @desc Public function for the top votes (actual month)
     */
    public function getActualTop(): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user";
        }

        $conf = VotesConfigModel::getInstance()->getConfig();

        if ($conf === null) {
            $reset = 0;
        } else {
            $reset = $conf->getReset();
        }

        switch ($reset) {
            case 1:
                $sql .= " WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE())";
                break;
            case 2:
                $sql .= " WHERE WEEK(cmw_votes_votes.votes_date) = WEEK(CURRENT_DATE())";
                break;
        }

        $sql .= " GROUP BY userId
                  ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";

        $sql .= "LIMIT " . $conf?->getTopShow();

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                UsersModel::getInstance()->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }


    /**
     * @return VotesPlayerStatsEntity[]
     * @desc Public function for the top votes (global)
     */
    public function getGlobalTop(): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY userId
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY userId 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        }
        $sql .= "LIMIT " . (new VotesConfigModel())->getConfig()?->getTopShow();

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    /**
     * @param int $rank
     * @return VotesPlayerStatsEntity[]
     * @desc Get the X rank player for actual month
     */
    public function getActualTopPlayerRank(int $rank): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE())
                    GROUP BY userId
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE())
                    GROUP BY userId
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        }

        $sql .= "LIMIT 1 OFFSET " . $rank - 1;

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    /**
     * @param int $rank
     * @return VotesPlayerStatsEntity[]
     * @desc Get the X rank player for the global top
     */
    public function getGlobalTopPlayerRank(int $rank): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY userId
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user GROUP BY userId 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC ";
        }

        $sql .= "LIMIT 1 OFFSET " . $rank - 1;

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getActualTopNoLimit(): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId 
                    FROM cmw_votes_votes JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, ANY_VALUE(cmw_users.user_id) AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getGlobalTopNoLimit(): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId
                    FROM cmw_votes_votes JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    GROUP BY cmw_users.user_pseudo ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, ANY_VALUE(cmw_users.user_id) AS userId 
                    FROM cmw_votes_votes JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    /**
     * @return VotesPlayerStatsEntity[]
     */
    public function getPreviousMonthTop(): array
    {
        if (DatabaseManager::isMariadb()) {
            $sql = "SELECT DISTINCT COUNT(cmw_votes_votes.votes_id) AS votes, cmw_users.user_id AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)  
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes, ANY_VALUE(cmw_users.user_id) AS userId FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_users.user_id = cmw_votes_votes.votes_id_user 
                    WHERE MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) 
                    GROUP BY cmw_users.user_pseudo 
                    ORDER BY COUNT(cmw_votes_votes.votes_id) DESC";
        }
        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($stats = $res->fetch()) {
            $toReturn[] = new VotesPlayerStatsEntity(
                $stats['votes'],
                (new UsersModel())->getUserById($stats['userId'])
            );
        }

        return $toReturn;
    }

    public function get3PreviousMonthsVotes(): array
    {
        $toReturn = [];

        $sqlCurrent = "SELECT COUNT(votes_id) AS votes FROM cmw_votes_votes WHERE MONTH(votes_date) = MONTH(CURRENT_DATE())";
        $sqlPrevious = "SELECT COUNT(votes_id) AS votes FROM cmw_votes_votes WHERE MONTH(votes_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
        $sql2MonthsAgo = "SELECT COUNT(votes_id) AS votes FROM cmw_votes_votes WHERE MONTH(votes_date) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)";

        $db = DatabaseManager::getInstance();

        //2MonthsAgo
        $req = $db->query($sql2MonthsAgo);
        $toReturn[] = $req->fetch()['votes'];

        //Previous
        $req = $db->query($sqlPrevious);
        $toReturn[] = $req->fetch()['votes'];

        //Current
        $req = $db->query($sqlCurrent);
        $toReturn[] = $req->fetch()['votes'];

        return $toReturn;
    }

    /**
     * @param int|string $user
     * @return int
     */
    public function getPlayerVotepoints(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT votes_votepoints_amount AS votepoints FROM cmw2.cmw_votes_votepoints 
                    WHERE votes_votepoints_id_user = :user LIMIT 1";
        } else {
            $sql = "SELECT cmw_votes_votepoints.votes_votepoints_amount AS votepoints FROM cmw_votes_votepoints 
                    JOIN cmw_users ON cmw_votes_votepoints.votes_votepoints_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user LIMIT 1";
        }

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votepoints'] ?? 0;
        }

        return 0;
    }

    /**
     * @param int $userId
     * @return int
     */
    public function getVotePointByUserId(int $userId): int
    {
        $sql = "SELECT votes_votepoints_amount FROM cmw_votes_votepoints WHERE votes_votepoints_id_user = :user";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if (!$req->execute(["user" => $userId])) {
            return 0;
        }

        $res = $req->fetch();

        if(!$res){
            return 0;
        }

        return $res['votes_votepoints_amount'] ?? 0;
    }

    /**
     * @param int|string $user
     * @return int
     */
    public function getPlayerCurrentVotes(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes FROM cmw_votes_votes 
                    WHERE cmw_votes_votes.votes_id_user = :user
                    AND MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) LIMIT 1";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_votes_votes.votes_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user
                    AND MONTH(cmw_votes_votes.votes_date) = MONTH(CURRENT_DATE()) LIMIT 1";
        }

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votes'] ?? 0;
        }

        return 0;
    }

    /**
     * @param int $rank
     * @return array
     */
    public function getRankTopVotePoints(int $rank): array
    {

        $sql = "SELECT cmw_votes_votepoints.votes_votepoints_amount AS votes, cmw_users.user_pseudo FROM cmw_votes_votepoints
                JOIN cmw_users ON cmw_users.user_id = cmw_votes_votepoints.votes_votepoints_id_user = cmw_users.user_id
                ORDER BY cmw_votes_votepoints.votes_votepoints_amount DESC ";

        $sql .= "LIMIT 1 OFFSET " . $rank - 1;

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);

        if (!$req->execute()) {
            return [];
        }

        return $req->fetch();
    }

    /**
     * @param int|string $user
     * @return int
     */
    public function getPlayerTotalVotes(int|string $user): int
    {
        if (is_int($user)) {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes FROM cmw_votes_votes 
                    WHERE cmw_votes_votes.votes_id_user = :user LIMIT 1";
        } else {
            $sql = "SELECT COUNT(cmw_votes_votes.votes_id) AS votes FROM cmw_votes_votes 
                    JOIN cmw_users ON cmw_votes_votes.votes_id_user = cmw_users.user_id 
                    WHERE cmw_users.user_pseudo = :user LIMIT 1";
        }

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);
        if ($req->execute(['user' => $user])) {
            return $req->fetch()['votes'] ?? 0;
        }

        return 0;
    }

}