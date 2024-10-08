<?php

namespace CMW\Controller\Votes\Api;

use CMW\Manager\Api\APIManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Model\Votes\VotesConfigModel;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Utils\Redirect;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * Class: @VotesApiController
 * @package Votes
 * @author Teyir
 * @version 0.0.1
 */
class VotesApiController extends AbstractController
{
    private const string usernameRegex = '[a-zA-Z0-9_]{2,16}$';

    /**
     * @return void
     * @desc Check if local API is enabled. If not we show a 404 error page
     */
    private function handleApiAuthorization(): void
    {
        if (!VotesConfigModel::getInstance()->getConfig()?->isEnableApi()) {
            Redirect::errorPage(404);
        }
    }

    #[Link('/getVotePoints/:pseudo', Link::GET, ['pseudo' => self::usernameRegex], scope: '/api/votes')]
    private function getVotePoints(string $pseudo): void
    {
        $this->handleApiAuthorization();

        $votePoints = VotesStatsModel::getInstance()->getPlayerVotepoints($pseudo);

        $toReturn = APIManager::createResponse(data: ['votepoints' => $votePoints]);
        print ($toReturn);
    }

    #[Link('/getTopVotesPoints/rank/:rank', Link::GET, ['rank' => '[0-9]+'], scope: '/api/votes')]
    private function getTopVotePoints(int $rank): void
    {
        $this->handleApiAuthorization();

        $votePoints = VotesStatsModel::getInstance()->getRankTopVotePoints($rank);

        $toReturn = APIManager::createResponse(data: $votePoints);
        print ($toReturn);
    }

    #[Link('/getPlayerCurrentVotes/:pseudo', Link::GET, ['pseudo' => self::usernameRegex], scope: '/api/votes')]
    private function getPlayerCurrentVotes(string $pseudo): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getPlayerCurrentVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ['votes' => $votes]);
        print ($toReturn);
    }

    #[Link('/getPlayerTotalVotes/:pseudo', Link::GET, ['pseudo' => self::usernameRegex], scope: '/api/votes')]
    private function getPlayerTotalVotes(string $pseudo): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getPlayerTotalVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ['votes' => $votes]);
        print ($toReturn);
    }

    /**
     * @param string $type
     * @return void
     * @desc Type: all, month, week, day, hour, minute
     */
    #[Link('/getVotes/:type', Link::GET, ['type' => '.*?'], scope: '/api/votes')]
    private function getVotes(#[ExpectedValues(['all', 'month', 'week', 'day', 'hour', 'minute'])] string $type): void
    {
        $this->handleApiAuthorization();

        $votes = count(VotesStatsModel::getInstance()->statsVotes($type));

        $toReturn = APIManager::createResponse(data: ['votes' => $votes]);
        print ($toReturn);
    }

    #[Link('/getTopActual', Link::GET, scope: '/api/votes')]
    private function getActualTop(): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getActualTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print ($toReturn);
    }

    #[Link('/getTopGlobal', Link::GET, scope: '/api/votes')]
    private function getTopGlobal(): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getGlobalTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print ($toReturn);
    }

    #[Link('/getTopVotesActual/:rank', Link::GET, ['rank' => '[0-9]+'], scope: '/api/votes')]
    private function getActualTopSpecificRank(int $rank): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getActualTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print ($toReturn);
    }

    #[Link('/getTopVotesGlobal/:rank', Link::GET, ['rank' => '[0-9]+'], scope: '/api/votes')]
    private function getGlobalTopSpecificRank(int $rank): void
    {
        $this->handleApiAuthorization();

        $votes = VotesStatsModel::getInstance()->getGlobalTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print ($toReturn);
    }
}
