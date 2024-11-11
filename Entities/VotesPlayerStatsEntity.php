<?php

namespace CMW\Entity\Votes;

use CMW\Entity\Users\UserEntity;
use CMW\Manager\Package\AbstractEntity;

class VotesPlayerStatsEntity extends AbstractEntity
{
    private ?int $votes;
    private ?UserEntity $user;

    /**
     * @param int|null $votes
     * @param UserEntity|null $player
     */
    public function __construct(?int $votes, ?UserEntity $player)
    {
        $this->votes = $votes;
        $this->user = $player;
    }

    /**
     * @return int|null
     */
    public function getVotes(): ?int
    {
        return $this->votes;
    }

    /**
     * @return UserEntity|null
     */
    public function getUser(): ?UserEntity
    {
        return $this->user;
    }
}
