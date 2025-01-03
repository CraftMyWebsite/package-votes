<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesSitesEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;

/**
 * Class @VotesSitesModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesSitesModel extends AbstractModel
{
    /**
     * @param string $title
     * @param int $time
     * @param string $idUnique
     * @param string $url
     * @param ?int $rewardsId
     * @return \CMW\Entity\Votes\VotesSitesEntity|null
     */
    public function addSite(string $title, int $time, string $idUnique, string $url, ?int $rewardsId): ?VotesSitesEntity
    {
        $var = [
            'title' => $title,
            'time' => $time,
            'id_unique' => $idUnique,
            'url' => $url,
            'rewards_id' => $rewardsId,
        ];

        $sql = 'INSERT INTO cmw_votes_sites (votes_sites_title, votes_sites_time, votes_sites_id_unique, 
                             votes_sites_url, votes_sites_rewards_id) VALUES (:title, :time, :id_unique, :url, :rewards_id)';
        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getSiteById($id);
        }

        return null;
    }

    /**
     * @param int $id
     * @return \CMW\Entity\Votes\VotesSitesEntity|null
     */
    public function getSiteById(int $id): ?VotesSitesEntity
    {
        $sql = 'SELECT * FROM cmw_votes_sites WHERE votes_sites_id=:id';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(['id' => $id])) {
            return null;
        }

        $res = $res->fetch();

        $rewards = (new VotesRewardsModel())->getRewardById($res['votes_sites_rewards_id']);

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

    /**
     * @return VotesSitesEntity[]
     */
    public function getSites(): array
    {
        $sql = 'SELECT votes_sites_id FROM cmw_votes_sites';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($site = $res->fetch()) {
            $toReturn[] = $this->getSiteById($site['votes_sites_id']);
        }

        return $toReturn;
    }

    /**
     * @param int $siteId
     * @param string $title
     * @param int $time
     * @param string $idUnique
     * @param string $url
     * @param ?int $rewardsId
     * @return \CMW\Entity\Votes\VotesSitesEntity|null
     */
    public function updateSite(int $siteId, string $title, int $time, string $idUnique, string $url, ?int $rewardsId): ?VotesSitesEntity
    {
        $info = [
            'id' => $siteId,
            'title' => $title,
            'time' => $time,
            'id_unique' => $idUnique,
            'url' => $url,
            'rewards_id' => $rewardsId,
        ];

        $sql = 'UPDATE cmw_votes_sites SET votes_sites_title=:title, votes_sites_time=:time, 
                           votes_sites_id_unique=:id_unique, votes_sites_url=:url, votes_sites_rewards_id=:rewards_id 
                       WHERE votes_sites_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getSiteById($siteId);
        }

        return null;
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteSite(int $id): void
    {
        $sql = 'DELETE FROM cmw_votes_sites WHERE votes_sites_id=:id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute(['id' => $id]);
    }
}
