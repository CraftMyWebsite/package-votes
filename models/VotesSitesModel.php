<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Database\DatabaseManager;

/**
 * Class @VotesSitesModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesSitesModel extends DatabaseManager
{

    //Add a new Website
    public function addSite(string $title, int $time, string $idUnique, string $url, int $rewardsId): ?VotesSitesEntity
    {
        $var = array(
            'title' => $title,
            'time' => $time,
            'id_unique' => $idUnique,
            'url' => $url,
            'rewards_id' => $rewardsId
        );

        $sql = "INSERT INTO cmw_votes_sites (votes_sites_title, votes_sites_time, votes_sites_id_unique, 
                             votes_sites_url, votes_sites_rewards_id) VALUES (:title, :time, :id_unique, :url, :rewards_id)";
        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getSiteById($id);
        }

        return null;
    }

    //Get all sites
    public function getSites(): array
    {
        $sql = "SELECT * FROM cmw_votes_sites";
        $db = self::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($site = $res->fetch()) {
            $toReturn[] = $this->getSiteById($site["votes_sites_id"]);
        }

        return $toReturn;
    }


    //Get a website
    public function getSiteById(int $id): ?VotesSitesEntity
    {


        $sql = "SELECT * FROM cmw_votes_sites WHERE votes_sites_id=:id";

        $db = self::getInstance();
        $res = $db->prepare($sql);


        if (!$res->execute(array("id" => $id))) {
            return null;
        }

        $res = $res->fetch();

        $rewards = (new VotesRewardsModel())->getRewardById($res["votes_sites_rewards_id"]);

        return new VotesSitesEntity(
            $res['votes_sites_id'],
            $res['votes_sites_title'],
            $res['votes_sites_url'],
            $res['votes_sites_time'],
            $res['votes_sites_id_unique'],
            $rewards,
            $res['votes_sites_date_create']
        );
    }

    //Edit a website
    public function updateSite(int $siteId, string $title, int $time, string $idUnique, string $url, int $rewardsId): ?VotesSitesEntity
    {
        $info = array(
            "id" => $siteId,
            "title" => $title,
            "time" => $time,
            "id_unique" => $idUnique,
            "url" => $url,
            "rewards_id" => $rewardsId
        );

        $sql = "UPDATE cmw_votes_sites SET votes_sites_title=:title, votes_sites_time=:time, 
                           votes_sites_id_unique=:id_unique, votes_sites_url=:url, votes_sites_rewards_id=:rewards_id 
                       WHERE votes_sites_id=:id";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getSiteById($siteId);
        }

        return null;
    }

    //Delete a site
    public function deleteSite(int $id): void
    {
        $sql = "DELETE FROM cmw_votes_sites WHERE votes_sites_id=:id";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute(array("id" => $id));
    }
}