<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use Exception;
use JsonException;

/**
 * Class @VotesRewardsModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesRewardsModel extends AbstractModel
{
    public function addReward(string $title, string $action): ?VotesRewardsEntity
    {
        $var = [
            'title' => $title,
            'action' => $action,
        ];

        $sql = "INSERT INTO cmw_votes_rewards (votes_rewards_title, votes_rewards_action) VALUES (:title, :action)";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getRewardById($id);
        }

        return null;
    }

    //Get a reward

    public function getRewardById(?int $id): ?VotesRewardsEntity
    {
        if ($id === null) {
            return null;
        }

        $sql = "SELECT * FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(["rewards_id" => $id])) {
            return null;
        }

        $res = $res->fetch();

        return new VotesRewardsEntity(
            $res['votes_rewards_rewards_id'],
            $res['votes_rewards_title'],
            $res['votes_rewards_action']
        );

    }

    public function deleteReward($id): void
    {
        $var = [
            "rewards_id" => $id,
        ];

        $sql = "DELETE FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function updateReward(int $rewardsId, string $title, string $action): ?VotesRewardsEntity
    {
        $var = [
            "rewards_id" => $rewardsId,
            "title" => $title,
            "action" => $action,
        ];

        $sql = "UPDATE cmw_votes_rewards SET votes_rewards_title=:title, votes_rewards_action=:action 
                         WHERE votes_rewards_rewards_id=:rewards_id";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($var)) {
            return $this->getRewardById($rewardsId);
        }

        return null;
    }

    public function selectReward(int $userId, int $idSite): void
    {
        //Select the reward action, rewards_id and id site with the site id
        $var = [
            "id" => $idSite,
        ];

        $sql = "SELECT cmw_votes_sites.votes_sites_rewards_id, cmw_votes_sites.votes_sites_id, 
                    cmw_votes_rewards.votes_rewards_action FROM cmw_votes_sites 
                    JOIN cmw_votes_rewards 
                        ON cmw_votes_sites.votes_sites_rewards_id = cmw_votes_rewards.votes_rewards_rewards_id 
                    WHERE cmw_votes_sites.votes_sites_id =:id LIMIT 1;";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            $rewardsId = $result['votes_sites_rewards_id'];
            $action = $result['votes_rewards_action'];

            //Detect type
            try {
                switch (json_decode($action, false, 512, JSON_THROW_ON_ERROR)->type) {

                    case "votepoints":
                        $this->giveRewardVotePoints($userId, json_decode($action, false, 512, JSON_THROW_ON_ERROR)->amount);
                        $this->setLog($userId, $rewardsId);
                        break;

                    case "votepoints-random":
                        $this->giveRewardVotePointsRandom($userId, json_decode($action, false, 512, JSON_THROW_ON_ERROR)->amount->min,
                            json_decode($action, false, 512, JSON_THROW_ON_ERROR)->amount->min);
                        $this->setLog($userId, $rewardsId);
                        break;
                }
            } catch (JsonException) {
                die("Internal Error. (selectReward() → VotesRewardsModel)");
            }
        }
    }

    public function giveRewardVotePoints(int $userId, int $amount): void
    {
        $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount)
            VALUES (:id_user, :amount)
            ON DUPLICATE KEY UPDATE
            votes_votepoints_amount = votes_votepoints_amount + :amount_on_update";

        $params = [
            'id_user' => $userId,
            'amount' => $amount,
            'amount_on_update' => $amount
        ];

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        $req->execute($params);
    }

    /**
     * @param int $userId
     * @param int $amount
     * @return bool
     */
    public function removeRewardVotePoints(int $userId , int $amount): bool
    {
        $sql = "UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount - :amount WHERE votes_votepoints_id_user = :user_id;";

        $db = DatabaseManager::getInstance();

        return $db->prepare($sql)->execute(array("amount" => $amount, "user_id" => $userId));
    }

    public function setLog(int $userId, int $rewardsId): void
    {
        $var = [
            "user_id" => $userId,
            "reward_id" => $rewardsId,
        ];

        $sql = "INSERT INTO cmw_votes_logs_rewards (votes_logs_rewards_user_id, votes_logs_rewards_reward_id) 
                    VALUES (:user_id, :reward_id)";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function giveRewardVotePointsRandom(int $userId, int $min, int $max): void
    {
        try {
            $amount = random_int($min, $max);
        } catch (Exception $e) {
            $amount = $max;
        }

        $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount)
            VALUES (:id_user, :amount)
            ON DUPLICATE KEY UPDATE
            votes_votepoints_amount = votes_votepoints_amount + :amount_on_update";

        $params = [
            'id_user' => $userId,
            'amount' => $amount,
            'amount_on_update' => $amount
        ];

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        $req->execute($params);
    }

    public function getRewards(): array
    {
        $sql = "SELECT * FROM cmw_votes_rewards";
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($reward = $res->fetch()) {
            $toReturn[] = $this->getRewardById($reward["votes_rewards_rewards_id"]);
        }

        return $toReturn;
    }
}