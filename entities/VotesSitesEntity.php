<?php

namespace CMW\Entity\Votes;

use CMW\Utils\Utils;

class VotesSitesEntity
{
    private int $siteId;
    private string $title;
    private string $url;
    private int $time;
    private string $idUnique;
    private ?VotesRewardsEntity $rewards;
    private string $dateCreate;

    /**
     * @param int $siteId
     * @param string $title
     * @param string $url
     * @param int $time
     * @param string $idUnique
     * @param \CMW\Entity\Votes\VotesRewardsEntity|null $rewardsId
     * @param string $dateCreate
     */
    public function __construct(int $siteId, string $title, string $url, int $time, string $idUnique, ?VotesRewardsEntity $rewardsId, string $dateCreate)
    {
        $this->siteId = $siteId;
        $this->title = $title;
        $this->url = $url;
        $this->time = $time;
        $this->idUnique = $idUnique;
        $this->rewards = $rewardsId;
        $this->dateCreate = $dateCreate;
    }

    /**
     * @return int
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time ?? 0;
    }

    /**
     * @return string
     */
    public function getTimeFormatted(): string
    {
        $t = $this->time;
        $h = floor($t / 60) ? floor($t / 60) . 'h ' : '';
        $m = $t % 60 ? $t % 60 . 'm' : '';

        return $h && $m ? $h . $m : $h . $m;
    }

    /**
     * @return string
     */
    public function getIdUnique(): string
    {
        return $this->idUnique;
    }

    /**
     * @return \CMW\Entity\Votes\VotesRewardsEntity|null
     */
    public function getRewards(): ?VotesRewardsEntity
    {
        return $this->rewards;
    }

    /**
     * @return string
     */
    public function getDateCreate(): string
    {
        return $this->dateCreate;
    }

    public function getSendLink(): string
    {
        return Utils::getEnv()->getValue("PATH_SUBFOLDER") . "vote/send/" . $this->siteId;
    }

}