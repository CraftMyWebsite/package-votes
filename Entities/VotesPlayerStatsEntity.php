<?php

namespace CMW\Entity\Votes;

use CMW\Entity\Users\UserEntity;

class VotesPlayerStatsEntity
{
    private ?int $votes;
    private ?UserEntity $user;

    /**
     * @param int|null $votes
     * @param \CMW\Entity\Users\UserEntity|null $player
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
     * @return \CMW\Entity\Users\UserEntity|null
     */
    public function getUser(): ?UserEntity
    {
        return $this->user;
    }
}
