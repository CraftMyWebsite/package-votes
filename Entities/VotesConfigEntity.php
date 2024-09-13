<?php

namespace CMW\Entity\Votes;

use JsonException;
use RuntimeException;
use function json_decode;
use function json_encode;

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

    /**
     * @return string
     */
    public function toJson(): string
    {
        $data = [
            'top_show' => $this->topShow,
            'reset' => $this->reset,
            'auto_top_reward_active' => $this->autoTopRewardActive,
            'auto_top_reward' => $this->autoTopReward,
            'enable_api' => $this->enableApi,
            'need_login' => $this->needLogin,
        ];

        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new \RuntimeException("Can't convert data to json. " . $e->getMessage());
        }
    }

    /**
     * @param string $data
     * @return self
     */
    public static function fromJson(string $data): self
    {
        try {
            $json = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException("Can't convert json to data. " . $e->getMessage());
        }

        return new self(
            $json['top_show'],
            $json['reset'],
            $json['auto_top_reward_active'],
            $json['auto_top_reward'],
            $json['enable_api'],
            $json['need_login']
        );
    }
}
