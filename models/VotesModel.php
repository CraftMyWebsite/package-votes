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
        $site = (new VotesSitesModel())->getSiteById($idSite);
        $targetDate = strtotime($this->getPlayerLastStoredVote($idUser, $idSite)['votes_date'] . ' + ' . $site?->getTime() . ' minutes');
        $currentDate = time();

        return $currentDate >= $targetDate;
    }

}
