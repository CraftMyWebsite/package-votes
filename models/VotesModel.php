<?php

namespace CMW\Model\Votes;

use CMW\Manager\Database\DatabaseManager;
use CMW\Utils\Utils;
use PDO;

/**
 * Class @VotesModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesModel extends DatabaseManager
{
    public function storeVote(int $idUser, string $idSite): void
    {
        $var = array(
            "id_user" => $idUser,
            "ip" => Utils::getClientIp(),
            "id_site" => $idSite
        );

        $sql = "INSERT INTO cmw_votes_votes (votes_id_user, votes_ip, votes_id_site) VALUES (:id_user, :ip, :id_site)";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    public function playerHasAVoteStored(int $idUser, int $idSite): bool
    {
        return count($this->getPlayerLastStoredVote($idUser, $idSite)) >= 1;
    }

    private function getPlayerLastStoredVote(int $idUser, int $idSite): array
    {
        $var = array(
            "idUser" => $idUser,
            "idSite" => $idSite
        );

        $sql = "SELECT * FROM `cmw_votes_votes` WHERE votes_id_user = :idUser AND votes_id_site = :idSite 
                ORDER BY votes_date DESC LIMIT 1";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $res = $req->fetch(PDO::FETCH_ASSOC);
            if (!$res) {
                return [];
            }
            return $res;
        }

        return [];
    }

    /**
     * @param int $idUser
     * @param int $idSite
     * @return bool
     * @desc Check if we can validate and store a new vote
     */
    public function validateThisVote(int $idUser, int $idSite): bool
    {
        $site = (new SitesModel())->getSiteById($idSite);
        $storeDate = strtotime($this->getPlayerLastStoredVote($idUser, $idSite)['votes_date']);
        $targetDate = strtotime($this->getPlayerLastStoredVote($idUser, $idSite)['votes_date'] . ' + ' . $site?->getTime() . ' minutes');

        return $targetDate <= $storeDate;
    }


    /**
     * @param string $url
     * @param string $idUnique
     * @param string $ipPlayer
     * @return bool
     * @throws \JsonException
     * @desc Return true if the player has voted
     */
    public function isVoteSend(string $url, string $idUnique, string $ipPlayer): bool
    {
        /* IGNORE HTTP ERROR */
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));

        //List of all websites:
        if (strpos($url, 'serveur-prive.net')) {
            $result = @file_get_contents("https://serveur-prive.net/api/vote/json/$idUnique/$ipPlayer");
            if ($result && ($result = json_decode($result, true, 512, JSON_THROW_ON_ERROR)) && (int)$result['status'] === 1) {
                return true;
            }
        } elseif (strpos($url, 'serveur-minecraft-vote.fr')) {
            $result = @file_get_contents("https://serveur-minecraft-vote.fr/api/v1/servers/$idUnique/vote/$ipPlayer");
            if ($result && ($result = json_decode($result, true, 512, JSON_THROW_ON_ERROR)) && $result['canVote'] === false) {
                return true;
            }
        } elseif (strpos($url, 'serveurs-mc.net')) {
            $result = @file_get_contents("https://serveurs-mc.net/api/hasVote/$idUnique/$ipPlayer/10");
            if ($result && ($result = json_decode($result, true, 512, JSON_THROW_ON_ERROR)) && $result['hasVote'] === false) {
                return true;
            }
        } elseif (strpos($url, 'top-serveurs.net')) {
            $result = @file_get_contents("https://api.top-serveurs.net/v1/votes/check-ip?server_token=$idUnique&ip=$ipPlayer", false, $context);
            if (json_decode($result, true, 512, JSON_THROW_ON_ERROR)['success']) {
                return true;
            }
        } elseif (strpos($url, 'serveursminecraft.org')) {
            $result = @file_get_contents("https://www.serveursminecraft.org/sm_api/peutVoter.php?id=$idUnique&ip=$ipPlayer");
            if ($result !== "true" && !empty($result)) {
                return true;
            }
        } elseif (strpos($url, 'liste-serveurs-minecraft.org')) {
            $result = @file_get_contents("https://api.liste-serveurs-minecraft.org/vote/vote_verification.php?server_id=$idUnique&ip=$ipPlayer");
            if ($result == 1) {
                return true;
            }
        } elseif (strpos($url, 'serveur-minecraft.com')) {
            $result = @file_get_contents("https://serveur-minecraft.com/api/1/vote/$idUnique/$ipPlayer");
            if ($result == 0) {
                return true;
            }
        }
        return false;
    }

}
