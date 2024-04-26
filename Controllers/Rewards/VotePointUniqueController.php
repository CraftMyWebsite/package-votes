<?php

namespace CMW\Controller\Votes\Rewards;

use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Manager\Package\AbstractController;
use CMW\Model\Votes\VotesRewardsModel;
use JetBrains\PhpStorm\NoReturn;


/**
 * Class: @VotePointUniqueController
 * @package Votes
 * @author Zomblard
 * @version 1.0
 */
class VotePointUniqueController extends AbstractController
{
    /**
     * @param \CMW\Entity\Votes\VotesRewardsEntity $reward
     * @param int $userId
     */
     #[NoReturn] public function giveUniqueVotePoints(VotesRewardsEntity $reward, int $userId): void
     {
         $amount = $reward->getAction();
         VotesRewardsModel::getInstance()->giveRewardVotePoints($userId,$amount);
     }
 }