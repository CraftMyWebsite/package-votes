<?php

namespace CMW\Mapper\Votes;

use CMW\Entity\Votes\Config\VotesConfigBlacklistEntity;
use CMW\Entity\Votes\VotesPlayerStatsEntity;
use function array_map;
use function in_array;

class VotesPublicPlayersStatsMapper
{

    /**
     * @param VotesPlayerStatsEntity[] $data
     * @param VotesConfigBlacklistEntity[] $blacklists
     * @return VotesPlayerStatsEntity[]
     */
    public static function removeIgnoredBlacklisted(array $data, array $blacklists): array
    {
        $toReturn = [];

        foreach ($data as $stat) {
            if (!in_array($stat->getUser()?->getId(), array_map(static fn($blacklist) => $blacklist->getUserId(), $blacklists), true)) {
                $toReturn[] = $stat;
            }
        }

        return $toReturn;
    }
}