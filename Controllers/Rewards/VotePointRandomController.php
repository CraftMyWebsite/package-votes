<?php

namespace CMW\Controller\Votes\Rewards;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Votes\VotesRewardsModel;
use JsonException;

/**
 * Class: @VotePointRandomController
 * @package Votes
 * @author Zomblard
 * @version 1.0
 */
class VotePointRandomController extends AbstractController
{
    /**
     * @param \CMW\Entity\Votes\VotesRewardsEntity $reward
     * @param int $userId
     */
    public function giveRandomVotePoints(VotesRewardsEntity $reward, int $userId): void
    {
        $action = $reward->getAction();

        try {
            $data = json_decode($action, false, 512, JSON_THROW_ON_ERROR);
            $min = $data->amount->min ?? 0;
            $max = $data->amount->max ?? 0;
        } catch (JsonException $e) {
            $min = 0;
            $max = 0;
        }

        VotesRewardsModel::getInstance()->giveRewardVotePointsRandom($userId, $min, $max);
    }
}
