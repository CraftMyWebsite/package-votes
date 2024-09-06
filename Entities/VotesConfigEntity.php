<?php

namespace CMW\Entity\Votes;

class VotesConfigEntity
{
    private int $topShow;
    private int $reset;
    private int $autoTopRewardActive;
    private ?string $autoTopReward;
    private bool $enableApi;
    private bool $needLogin;

    /**
     * @param int $topShow
     * @param int $reset
     * @param int $autoTopRewardActive
     * @param string|null $autoTopReward
     * @param bool $enableApi
     * @param bool $needLogin
     */
    public function __construct(int $topShow, int $reset, int $autoTopRewardActive, ?string $autoTopReward, bool $enableApi, bool $needLogin)
    {
        $this->topShow = $topShow;
        $this->reset = $reset;
        $this->autoTopRewardActive = $autoTopRewardActive;
        $this->autoTopReward = $autoTopReward;
        $this->enableApi = $enableApi;
        $this->needLogin = $needLogin;
    }

    /**
     * @return int
     */
    public function getTopShow(): int
    {
        return $this->topShow;
    }

    /**
     * @return int
     */
    public function getReset(): int
    {
        return $this->reset;
    }

    /**
     * @return int
     */
    public function getAutoTopRewardActive(): int
    {
        return $this->autoTopRewardActive;
    }

    /**
     * @return string|null
     */
    public function getAutoTopReward(): ?string
    {
        return $this->autoTopReward;
    }

    /**
     * @return bool
     */
    public function isEnableApi(): bool
    {
        return $this->enableApi;
    }

    public function isNeedLogin(): bool
    {
        return $this->needLogin;
    }
}
