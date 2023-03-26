<?php

namespace CMW\Model\Votes;

use CMW\Manager\Database\DatabaseManager;
use JsonException;

/**
 * Class @CheckVotesModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class CheckVotesModel extends DatabaseManager
{

    /**
     * @param string $url
     * @param string $idUnique
     * @param string $ipPlayer
     * @return bool
     * @desc Return true if the player has voted
     */
    public function isVoteSend(string $url, string $idUnique, string $ipPlayer): bool
    {
        /* IGNORE HTTP ERROR */
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        //List of all websites:

        if (self::checkUrl($url, 'serveur-prive.net')) {
            return self::checkJsonData("https://serveur-prive.net/api/vote/json/$idUnique/$ipPlayer",'status', 1);
        }

        if (self::checkUrl($url, 'serveur-minecraft-vote.fr')) {
            return self::checkJsonData("https://serveur-minecraft-vote.fr/api/v1/servers/$idUnique/vote/$ipPlayer", 'canVote', false);
        }

        if (self::checkUrl($url, 'serveurs-mc.net')) {
            return self::checkJsonData("https://serveurs-mc.net/api/hasVote/$idUnique/$ipPlayer/10", 'hasVote', false);
        }

        if (self::checkUrl($url, 'top-serveurs.net')) {
            return self::checkJsonData("https://api.top-serveurs.net/v1/votes/check-ip?server_token=$idUnique&ip=$ipPlayer", 'success', true);
        }

        if (self::checkUrl($url, 'serveursminecraft.org')) {
            return !self::checkPlainData("https://www.serveursminecraft.org/sm_api/peutVoter.php?id=$idUnique&ip=$ipPlayer", "true")
                && !self::checkPlainData("https://www.serveursminecraft.org/sm_api/peutVoter.php?id=$idUnique&ip=$ipPlayer", "");
        }

        if (self::checkUrl($url, 'liste-serveurs-minecraft.org')) {
            return self::containData("https://api.liste-serveurs-minecraft.org/vote/vote_verification.php?server_id=$idUnique&ip=$ipPlayer", 1);
        }

        if (self::checkUrl($url, 'serveur-minecraft.com')) {
            return self::containData("https://serveur-minecraft.com/api/1/vote/$idUnique/$ipPlayer", 0);
        }

        if (self::checkUrl($url, 'liste-serveurs-minecraft.com')) {
            return self::checkJsonData("https://www.liste-minecraft-serveurs.com/Api/Worker/id_server/$idUnique/ip/$ipPlayer", 'result', 200);
        }

        if (self::checkUrl($url, 'serveurs-minecraft.org')) {
            return self::checkPlainData("https://www.serveurs-minecraft.org/api/is_valid_vote.php?id=$idUnique&ip=$ipPlayer&duration=5", 1);
        }

        return false;
    }

    /**
     * @param string $url
     * @param string $siteId
     * @return bool
     * @desc Check if siteId is good
     */
    public function testSiteId(string $url, string $siteId): bool
    {
        /* IGNORE HTTP ERROR */
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        //List of all websites:
        if (self::checkUrl($url, 'serveur-prive.net')) {
            return self::checkJsonData("https://serveur-prive.net/api/stats/json/$siteId/position",'status', 1);
        }

        if (self::checkUrl($url, 'serveur-minecraft-vote.fr')) {
            return self::checkStatusCode("https://serveur-minecraft-vote.fr/api/v1/servers/$siteId", 200);
        }

        if (self::checkUrl($url, 'serveurs-mc.net')) {
            return self::checkStatusCode("https://serveurs-mc.net/api/hasVote/$siteId/0.0.0.0/10", 200);
        }

        if (self::checkUrl($url, 'top-serveurs.net')) {
            return self::checkStatusCode("https://api.top-serveurs.net/v1/servers/$siteId/players-ranking", 200);
        }

        if (self::checkUrl($url, 'serveursminecraft.org')) {
            return self::checkStatusCode("https://www.serveursminecraft.org/serveur/$siteId", 200);
        }

        if (self::checkUrl($url, 'liste-serveurs-minecraft.org')) {
            return !self::checkPlainData("https://api.liste-serveurs-minecraft.org/widget/index.php?id=$siteId", '');
        }

        if (self::checkUrl($url, 'serveur-minecraft.com')) {
            return self::containData("https://serveur-minecraft.com/$siteId", "<title>An Error Occurred: Not Found</title>");
        }

        if (self::checkUrl($url, 'liste-serveurs-minecraft.com')) {
            return self::checkJsonData("https://www.liste-minecraft-serveurs.com/Api/Worker/id_server/$siteId/ip/0.0.0.0", 'result', 400);
        }

        if (self::checkUrl($url, 'serveurs-minecraft.org')) {
            return self::checkPlainData("https://www.serveurs-minecraft.org/api/is_online.php?id=$siteId&format=json", 1);
        }


        return false;
    }

    /**
     * @param string $url
     * @param string $valueIndex
     * @param mixed $expectedValue
     * @return bool
     * @desc Return json array
     */
    public static function checkJsonData(string $url, string $valueIndex, mixed $expectedValue): bool
    {
        /* IGNORE HTTP ERROR */
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        $result = @file_get_contents($url, false, $context);

        try {
            if ($result && ($result = json_decode($result, true, 512, JSON_THROW_ON_ERROR))
                && $result[$valueIndex] == $expectedValue) {
                return true;
            }
        } catch (JsonException) {
        }

        return false;
    }

    /**
     * @param string $url
     * @param mixed $expectedValue
     * @return bool
     */
    public static function checkPlainData(string $url, mixed $expectedValue): bool
    {
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));
        return @file_get_contents($url, false, $context) == $expectedValue;
    }


    /**
     * @param string $url
     * @param int $expectedValue
     * @return bool
     * @des Return status code
     */
    public static function checkStatusCode(string $url, int $expectedValue): bool
    {
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return (int)$httpCode === $expectedValue;
    }

    public static function containData(string $url, mixed $expectedData): bool
    {
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        return strpos(@file_get_contents($url, false, $context), $expectedData);
    }

    /**
     * @param string $url
     * @param $siteName
     * @return bool
     */
    public static function checkUrl(string $url, $siteName): bool
    {
        return strpos($url, $siteName);
    }


}