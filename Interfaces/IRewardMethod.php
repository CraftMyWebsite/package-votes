<?php

namespace CMW\Interface\Votes;

use CMW\Entity\Users\UserEntity;
use CMW\Entity\Votes\VotesRewardsEntity;
use CMW\Entity\Votes\VotesSitesEntity;

interface IRewardMethod
{
    /**
     * @return string
     * @desc The name of the payment method
     * @example "Points de votes"
     */
    public function name(): string;

    /**
     * @return string
     * @desc The variable name
     */
    public function varName(): string;

    /**
     * @param ?int $rewardId
     * @return void
     * @desc Include the config widgets for the shop add items
     * @example require_once EnvManager::getInstance()->getValue("DIR") . "App/Package/Votes/Views/Elements/votePointUnique.config.inc.view.php";
     */
    public function includeRewardConfigWidgets(?int $rewardId): void;

    /**
     * @return ?string
     * @desc is useful when you need a custom action like a json array, otherwise return null
     */
    public function execRewardActionLogic(): ?string;

    /**
     * @param \CMW\Entity\Votes\VotesRewardsEntity $reward
     * @param \CMW\Entity\Votes\VotesSitesEntity $site
     * @param int $userId
     * @return void
     * @desc Do exec on reward
     */
    public function execReward(VotesRewardsEntity $reward, VotesSitesEntity $site, int $userId): void;
}