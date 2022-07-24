<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Model\Manager;

/**
 * Class @RewardsModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class RewardsModel extends Manager
{

    public function addReward(string $title, string $action): ?VotesRewardsEntity
    {
        $var = array(
            'title' => $title,
            'action' => $action
        );

        $sql = "INSERT INTO cmw_votes_rewards (votes_rewards_title, votes_rewards_action) VALUES (:title, :action)";

        $db = Manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getRwardById($id);
        }

        return null;
    }

    //Get a reward

    public function getRwardById(int $id): ?VotesRewardsEntity
    {

        $sql = "SELECT * FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id";

        $db = Manager::dbConnect();
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

        $db = Manager::dbConnect();
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

        $db = Manager::dbConnect();
        $req = $db->prepare($sql);
        if ($req->execute($var)) {
            return $this->getRwardById($rewardsId);
        }

        return null;
    }

    public function selectReward(int $userId, int $idSite)
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

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $result = $req->fetch();
            $rewardsId = $result['rewards_id'];
            $action = $result['action'];
        }


        //Detect type
        switch (json_decode($result['action'])->type) {

            case "votepoints":
                $this->giveRewardVotePoints($userId, json_decode($result['action'])->amount);
                break;

            case "votepoints-random":
                $this->giveRewardVotePointsRandom($userId, json_decode($result['action'])->amount->min, json_decode($result['action'])->amount->min);
                break;

        }

    }

    public function giveRewardVotePoints(int $userId, int $amount): void
    {
        //If the player has never get a reward
        if ($this->detectFirstVotePointsReward($userId)) {
            $var = array(
                "id_user" => $userId,
                "amount" => $amount
            );

            $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount) 
                        VALUES (:id_user, :amount)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);

        } else { //If the player has already get a reward
            $var = array(
                "id_user" => $userId,
                "amount" => $amount
            );

            $sql = "UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount+:amount
                            WHERE votes_votepoints_id_user=:id_user";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);
        }

    }

    public function detectFirstVotePointsReward(int $userId): bool
    {
        $var = array(
            "id_user" => $userId
        );

        $sql = "SELECT votes_votepoints_id_user FROM cmw_votes_votepoints WHERE votes_votepoints_id_user=:id_user";

        $db = manager::dbConnect();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $lines = $req->fetchAll();

            if (count($lines) <= 0) {
                return true;
            } else {
                return false;
            }

        }

        return false;
    }

    public function giveRewardVotePointsRandom(int $userId, int $min, int $max): void
    {
        $amount = rand($min, $max);

        //If the player has never get a reward
        if ($this->detectFirstVotePointsReward($userId)) {
            $var = array(
                "id_user" => $userId,
                "amount" => $amount
            );

            $sql = "INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount) 
                        VALUES (:id_user, :amount)";

            $db = manager::dbConnect();
            $req = $db->prepare($sql);
            $req->execute($var);

        } else { //If the player has already get a reward
            $var = array(
                "id_user" => $userId,
                "amount" => $amount
            );

            $sql = "UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount+:amount 
                            WHERE votes_votepoints_id_user=:id_user";

            $db = manager::dbConnect();
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

        $db = manager::dbConnect();
        $req = $db->prepare($sql);
        $req->execute($var);
    }



    public function getRewards(): array
    {
        $sql = "SELECT * FROM cmw_votes_rewards";
        $db = Manager::dbConnect();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return array();
        }

        $toReturn = array();

        while ($reward = $res->fetch()) {
            $toReturn[] = $this->getRwardById($reward["votes_rewards_rewards_id"]);
        }

        return $toReturn;
    }
}