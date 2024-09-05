<?php

namespace CMW\Controller\Votes\Admin;

use CMW\Manager\Api\APIManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Requests\Request;
use CMW\Manager\Router\Link;
use CMW\Model\Votes\VotesStatsModel;
use JetBrains\PhpStorm\ExpectedValues;

/**
 * Class: @VotesController
 * @package Votes
 * @author Teyir
 * @version 1.0
 */
class VotesApiController extends AbstractController
{
    private const string usernameRegex = "^[a-zA-Z0-9_]{2,16}$";

    #[Link("/getVotePoints/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    private function getVotePoints(Request $request, string $pseudo): void
    {
        $votePoints = VotesStatsModel::getInstance()->getPlayerVotepoints($pseudo);

        $toReturn = APIManager::createResponse(data: ["votepoints" => $votePoints]);
        print($toReturn);
    }

    #[Link("/getTopVotesPoints/rank/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    private function getTopVotePoints(Request $request, int $rank): void
    {
        $votePoints = VotesStatsModel::getInstance()->getRankTopVotePoints($rank);

        $toReturn = APIManager::createResponse(data: $votePoints);
        print($toReturn);
    }

    #[Link("/getPlayerCurrentVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    private function getPlayerCurrentVotes(Request $request, string $pseudo): void
    {
        $votes = VotesStatsModel::getInstance()->getPlayerCurrentVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }

    #[Link("/getPlayerTotalVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    private function getPlayerTotalVotes(Request $request, string $pseudo): void
    {
        $votes = VotesStatsModel::getInstance()->getPlayerTotalVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }

    /**
     * @param \CMW\Manager\Requests\Request $request
     * @param string $type
     * @return void
     * @desc Type: all, month, week, day, hour, minute
     */
    #[Link("/getVotes/:type", Link::GET, ["type" => ".*?"], scope: "/api/votes")]
    private function getVotes(Request $request, #[ExpectedValues(["all", "month", "week", "day", "hour", "minute"])] string $type): void
    {
        $votes = count(VotesStatsModel::getInstance()->statsVotes($type));

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }


    #[Link("/getTopActual", Link::GET, scope: "/api/votes")]
    private function getActualTop(): void
    {
        $votes = VotesStatsModel::getInstance()->getActualTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopGlobal", Link::GET, scope: "/api/votes")]
    private function getTopGlobal(): void
    {
        $votes = VotesStatsModel::getInstance()->getGlobalTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopVotesActual/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    private function getActualTopSpecificRank(Request $request, int $rank): void
    {
        $votes = VotesStatsModel::getInstance()->getActualTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopVotesGlobal/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    private function getGlobalTopSpecificRank(Request $request, int $rank): void
    {
        $votes = VotesStatsModel::getInstance()->getGlobalTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }
}
