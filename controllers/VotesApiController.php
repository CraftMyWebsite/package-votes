<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Manager\Api\APIManager;
use CMW\Model\Votes\VotesStatsModel;
use CMW\Router\Link;
use JetBrains\PhpStorm\ExpectedValues;


/**
 * Class: @VotesController
 * @package Votes
 * @author Teyir
 * @version 1.0
 */
class VotesApiController extends CoreController
{

    private const usernameRegex = "^[a-zA-Z0-9_]{2,16}$";

    #[Link("/getVotePoints/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    public function getVotePoints(string $pseudo): void
    {
        $votePoints = (new VotesStatsModel())->getPlayerVotepoints($pseudo);

        $toReturn = APIManager::createResponse(data: ["votepoints" => $votePoints]);
        print($toReturn);
    }

    #[Link("/getTopVotesPoints/rank/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    public function getTopVotePoints(int $rank): void
    {
        $votePoints = (new VotesStatsModel())->getRankTopVotePoints($rank);

        $toReturn = APIManager::createResponse(data: $votePoints);
        print($toReturn);
    }

    #[Link("/getPlayerCurrentVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    public function getPlayerCurrentVotes(string $pseudo): void
    {
        $votes = (new VotesStatsModel())->getPlayerCurrentVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }

    #[Link("/getPlayerTotalVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    public function getPlayerTotalVotes(string $pseudo): void
    {
        $votes = (new VotesStatsModel())->getPlayerTotalVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }

    /**
     * @param string $type
     * @return void
     * @desc Type: all, month, week, day, hour, minute
     */
    #[Link("/getVotes/:type", Link::GET, ["type" => ".*?"], scope: "/api/votes")]
    public function getVotes(#[ExpectedValues(["all", "month", "week", "day", "hour", "minute"])] string $type): void
    {
        $votes = count((new VotesStatsModel())->statsVotes($type));

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }


    #[Link("/getTopActual", Link::GET, scope: "/api/votes")]
    public function getActualTop(): void
    {
        $votes = (new VotesStatsModel())->getActualTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopGlobal", Link::GET, scope: "/api/votes")]
    public function getTopGlobal(): void
    {
        $votes = (new VotesStatsModel())->getGlobalTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopVotesActual/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    public function getActualTopSpecificRank(int $rank): void
    {
        $votes = (new VotesStatsModel())->getActualTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopVotesGlobal/:rank", Link::GET, ["rank" => "[0-9]+"], scope: "/api/votes")]
    public function getGlobalTopSpecificRank(int $rank): void
    {
        $votes = (new VotesStatsModel())->getGlobalTopPlayerRank($rank);

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

}
