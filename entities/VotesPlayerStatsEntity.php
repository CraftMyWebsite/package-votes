<?php

namespace CMW\Entity\Votes;

class VotesPlayerStatsEntity
{

    private int $votes;
    private string $pseudo;
    private string $email;

    /**
     * @param int $votes
     * @param string $pseudo
     * @param string $email
     */
    public function __construct(int $votes, string $pseudo, string $email)
    {
        $this->votes = $votes;
        $this->pseudo = $pseudo;
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

}
