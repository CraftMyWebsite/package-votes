<?php

namespace CMW\Entity\Votes;

class VotesRewardsEntity
{
    private ?int $rewardsId;
    private string $varName;
    private ?string $title;
    private ?string $action;

    /**
     * @param ?int $rewardsId
     * @param string $varName
     * @param ?string $title
     * @param ?string $action
     */
    public function __construct(?int $rewardsId, string $varName, ?string $title, ?string $action)
    {
        $this->rewardsId = $rewardsId;
        $this->title = $title;
        $this->varName = $varName;
        $this->action = $action;
    }

    /**
     * @return ?int
     */
    public function getRewardsId(): ?int
    {
        return $this->rewardsId;
    }

    /**
     * @return ?string
     */
    public function getVarName(): ?string
    {
        return $this->varName;
    }

    /**
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return ?string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }
}