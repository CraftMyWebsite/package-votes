<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Manager\Database\DatabaseManager;
use Exception;
use JsonException;

/**
 * Class @RewardsModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class RewardsModel extends DatabaseManager
{

    public function addReward(string $title, string $action): ?VotesRewardsEntity
    {
        $var = array(
            'title' => $title,
            'action' => $action
        );

        $sql = "INSERT INTO cmw_votes_rewards (votes_rewards_title, votes_rewards_action) VALUES (:title, :action)";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getRewardById($id);
        }

        return null;
    }

    //Get a reward

    public function getRewardById(int $id): ?VotesRewardsEntity
    {

        $sql = "SELECT * FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id";

        $db = self::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(array("rewards_id" => $id))) {
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
        $var = array(
            "rewards_id" => $id
        );

        $sql = "DELETE FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function updateReward(int $rewardsId, string $title, string $action): ?VotesRewardsEntity
    {
        $var = array(
            "rewards_id" => $rewardsId,
            "title" => $title,
            "action" => $action
        );

        $sql = "UPDATE cmw_votes_rewards SET votes_rewards_title=:title, votes_rewards_action=:action 
                         WHERE votes_rewards_rewards_id=:rewards_id";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($var)) {
            return $this->getRewardById($rewardsId);
        }

        return null;
    }

    public function selectReward(int $userId, int $idSite): void
    {

        //Select the reward action, rewards_id and id site with the site id
        $var = array(
            "id" => $idSite
        );

        $sql = "SELECT cmw_votes_sites.votes_sites_rewards_id, cmw_votes_sites.votes_sites_id, 
                    cmw_votes_rewards.votes_rewards_action FROM cmw_votes_sites 
                    JOIN cmw_votes_rewards 
                        ON cmw_votes_sites.votes_sites_rewards_id = cmw_votes_rewards.votes_rewards_rewards_id 
                    WHERE cmw_votes_sites.votes_sites_id =:id LIMIT 1;";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            $rewardsId = $result['rewards_id'];
            $action = $result['action'];


            //Detect type
            try {
                switch (json_decode($result['action'], false, 512, JSON_THROW_ON_ERROR)->type) {

                    case "votepoints":
                        $this->giveRewardVotePoints($userId, json_decode($result['action'], false, 512, JSON_THROW_ON_ERROR)->amount);
                        break;

                    case "votepoints-random":
                        $this->giveRewardVotePointsRandom($userId, json_decode($result['action'], false, 512, JSON_THROW_ON_ERROR)->amount->min,
                            json_decode($result['action'], false, 512, JSON_THROW_ON_ERROR)->amount->min);
                        break;

                }
            } catch (JsonException $e) {
            }
        }


    }

    public function giveRewardVotePoints(int $userId, int $amount): void
    {
        //If the player has never got a reward
        $var = array(
            "id_user" => $userId,
            "amount" => $amount
        );
        if ($this->detectFirstVotePointsReward($userId)) {

            $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount) 
                        VALUES (:id_user, :amount)";

        } else { //If the player has already got a reward

            $sql = "UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount+:amount
                            WHERE votes_votepoints_id_user=:id_user";

        }
        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);

    }

    public function detectFirstVotePointsReward(int $userId): bool
    {
        $var = array(
            "id_user" => $userId
        );

        $sql = "SELECT votes_votepoints_id_user FROM cmw_votes_votepoints WHERE votes_votepoints_id_user=:id_user";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $lines = $req->fetchAll();

            return count($lines) <= 0;

        }

        return false;
    }

    public function giveRewardVotePointsRandom(int $userId, int $min, int $max): void
    {
        try {
            $amount = random_int($min, $max);
        } catch (Exception $e) {
            $amount = $max;
        }

        //If the player has never got a reward
        $var = array(
            "id_user" => $userId,
            "amount" => $amount
        );
        if ($this->detectFirstVotePointsReward($userId)) {

            $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount) 
                        VALUES (:id_user, :amount)";

            $db = self::getInstance();
            $req = $db->prepare($sql);
            $req->execute($var);

        } else { //If the player has already got a reward

            $sql = "UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount+:amount 
                            WHERE votes_votepoints_id_user=:id_user";

            $db = self::getInstance();
            $req = $db->prepare($sql);
            $req->execute($var);

            $rewardsId = $db->lastInsertId();
            $this->setLog($userId, $rewardsId);
        }

    }

    //Reward → votepoints random

    public function setLog(int $userId, int $rewardsId): void
    {
        $var = array(
            "user_id" => $userId,
            "reward_id" => $rewardsId
        );

        $sql = "INSERT INTO cmw_votes_logs_rewards (votes_logs_rewards_user_id, votes_logs_rewards_reward_id) 
                    VALUES (:user_id, :reward_id)";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }


    public function getRewards(): array
    {
        $sql = "SELECT * FROM cmw_votes_rewards";
        $db = self::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($reward = $res->fetch()) {
            $toReturn[] = $this->getRewardById($reward["votes_rewards_rewards_id"]);
        }

        return $toReturn;
    }
}