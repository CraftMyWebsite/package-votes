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
    public function getRewardById(?int $id): ?VotesRewardsEntity
    {
        if ($id === null) {
            return null;
        }

        $sql = 'SELECT * FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id';

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);

        if (!$res->execute(['rewards_id' => $id])) {
            return null;
        }

        $res = $res->fetch();

        return new VotesRewardsEntity(
            $res['votes_rewards_rewards_id'],
            $res['votes_rewards_var_name'],
            $res['votes_rewards_title'],
            $res['votes_rewards_action']
        );
    }

    public function deleteReward($id): void
    {
        $var = [
            'rewards_id' => $id,
        ];

        $sql = 'DELETE FROM cmw_votes_rewards WHERE votes_rewards_rewards_id=:rewards_id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function addReward(string $title, string $action, string $varName): ?VotesRewardsEntity
    {
        $var = [
            'title' => $title,
            'action' => $action,
            'varName' => $varName
        ];

        $sql = 'INSERT INTO cmw_votes_rewards (votes_rewards_title, votes_rewards_action, votes_rewards_var_name) VALUES (:title, :action, :varName)';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $id = $db->lastInsertId();
            return $this->getRewardById($id);
        }

        return null;
    }

    public function updateReward(int $rewardsId, string $title, string $action, string $varName): ?VotesRewardsEntity
    {
        $var = [
            'rewards_id' => $rewardsId,
            'title' => $title,
            'action' => $action,
            'varName' => $varName
        ];

        $sql = 'UPDATE cmw_votes_rewards SET votes_rewards_title=:title, votes_rewards_action=:action, votes_rewards_var_name=:varName
                         WHERE votes_rewards_rewards_id=:rewards_id';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($var)) {
            return $this->getRewardById($rewardsId);
        }

        return null;
    }

    public function giveRewardVotePoints(int $userId, int $amount): bool
    {
        $sql = 'INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount)
            VALUES (:id_user, :amount)
            ON DUPLICATE KEY UPDATE
            votes_votepoints_amount = votes_votepoints_amount + :amount_on_update';

        $params = [
            'id_user' => $userId,
            'amount' => $amount,
            'amount_on_update' => $amount
        ];

        $db = DatabaseManager::getInstance();

        return $db->prepare($sql)->execute($params);
    }

    /**
     * @param int $userId
     * @param int $amount
     * @return bool
     */
    public function removeRewardVotePoints(int $userId, int $amount): bool
    {
        $sql = 'UPDATE cmw_votes_votepoints SET votes_votepoints_amount = votes_votepoints_amount - :amount WHERE votes_votepoints_id_user = :user_id;';

        $db = DatabaseManager::getInstance();

        return $db->prepare($sql)->execute(array('amount' => $amount, 'user_id' => $userId));
    }

    public function setLog(int $userId, int $rewardsId): void
    {
        $var = [
            'user_id' => $userId,
            'reward_id' => $rewardsId,
        ];

        $sql = 'INSERT INTO cmw_votes_logs_rewards (votes_logs_rewards_user_id, votes_logs_rewards_reward_id) 
                    VALUES (:user_id, :reward_id)';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function giveRewardVotePointsRandom(int $userId, int $min, int $max): int
    {
        try {
            $amount = random_int($min, $max);
        } catch (Exception $e) {
            $amount = $max;
        }

        $sql = 'INSERT INTO cmw_votes_votepoints (votes_votepoints_id_user, votes_votepoints_amount)
            VALUES (:id_user, :amount)
            ON DUPLICATE KEY UPDATE
            votes_votepoints_amount = votes_votepoints_amount + :amount_on_update';

        $params = [
            'id_user' => $userId,
            'amount' => $amount,
            'amount_on_update' => $amount
        ];

        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);

        if ($req->execute($params)) {
            return $amount;
        }

        return 0;
    }

    public function getRewards(): array
    {
        $sql = 'SELECT * FROM cmw_votes_rewards';
        $db = DatabaseManager::getInstance();

        $res = $db->prepare($sql);

        if (!$res->execute()) {
            return [];
        }

        $toReturn = [];

        while ($reward = $res->fetch()) {
            $toReturn[] = $this->getRewardById($reward['votes_rewards_rewards_id']);
        }

        return $toReturn;
    }
}
