<?php

namespace CMW\Entity\Votes;

class VotesPlayerStatsEntity
{

    private ?int $votes;
    private ?string $pseudo;
    private ?string $email;

    /**
     * @param int|null $votes
     * @param string|null $pseudo
     * @param string|null $email
     */
    public function __construct(?int $votes, ?string $pseudo, ?string $email)
    {
        $this->votes = $votes;
        $this->pseudo = $pseudo;
        $this->email = $email;
    }

    /**
     * @return int|null
     */
    public function getVotes(): ?int
    {
        return $this->votes;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

}
