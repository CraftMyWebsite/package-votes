<?php

namespace CMW\Controller\Votes;

use CMW\Controller\Core\CoreController;
use CMW\Manager\Api\APIManager;
use CMW\Manager\Database\DatabaseManager;
use CMW\Model\Users\UsersModel;
use CMW\Model\Votes\StatsModel;
use CMW\Model\Votes\VotesModel;
use CMW\Router\Link;
use CMW\Utils\Utils;
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
        $votePoints = (new StatsModel())->getPlayerVotepoints($pseudo);

        $toReturn = APIManager::createResponse(data: ["votepoints" => $votePoints]);
        print($toReturn);
    }

    #[Link("/getPlayerCurrentVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    public function getPlayerCurrentVotes(string $pseudo): void
    {
        $votes = (new StatsModel())->getPlayerCurrentVotes($pseudo);

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }

    #[Link("/getPlayerTotalVotes/:pseudo", Link::GET, ["pseudo" => self::usernameRegex], scope: "/api/votes")]
    public function getPlayerTotalVotes(string $pseudo): void
    {
        $votes = (new StatsModel())->getPlayerTotalVotes($pseudo);

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
        $votes = count((new StatsModel())->statsVotes($type));

        $toReturn = APIManager::createResponse(data: ["votes" => $votes]);
        print($toReturn);
    }


    #[Link("/getActualTop", Link::GET, scope: "/api/votes")]
    public function getActualTop(): void
    {
        $votes = (new StatsModel())->getActualTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

    #[Link("/getTopGlobal", Link::GET, scope: "/api/votes")]
    public function getTopGlobal(): void
    {
        $votes = (new StatsModel())->getGlobalTop();

        $toReturn = APIManager::createResponse(data: $votes);
        print($toReturn);
    }

}
