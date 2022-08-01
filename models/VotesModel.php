<?php

namespace CMW\Model\Votes;

use CMW\Manager\Database\DatabaseManager;

/**
 * Class @VotesModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesModel extends DatabaseManager
{
    public int $id;
    public string $title;
    public string $url;
    public int $time;
    public string $idUnique;
    public int $rewardsId;
    public string $dateCreate;


    public int $idSite;
    public int $idUser;
    public string $pseudo;
    public string $ipPlayer;


    public function getSite($url): array
    {
        $var = array(
            "url" => $url
        );

        $sql = "SELECT * FROM cmw_votes_sites WHERE votes_sites_url=:url";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            return $req->fetch();
        }

        return [];
    }

    public function hasVoted()
    {
        //Check if the player has already vote for the website id
        $var = array(
            "id_user" => $this->idUser,
            "id_site" => $this->idSite
        );

        $sql = "SELECT * FROM cmw_votes_votes WHERE votes_id_user = :id_user AND votes_id_site = :id_site";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $lines = $req->fetchAll();

            if (count($lines) <= 0) {
                return "NEW_VOTE";
            }

        }

        //Get current date
        $currentDate = time();

        //Get the vote time
        $var = array(
            "id" => $this->idSite
        );

        $sql = "SELECT votes_sites_time FROM cmw_votes_sites WHERE votes_sites_id = :id";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $res = $req->fetch();
            $time = $res['time']; // Vote time

        }

        //Get the last vote
        $var = array(
            "id_user" => $this->idUser,
            "id_site" => $this->idSite
        );

        $sql = "SELECT votes_date FROM cmw_votes_votes WHERE votes_id_user = :id_user AND votes_id_site = :id_site 
                                       ORDER BY `cmw_votes_votes`.`votes_date` DESC LIMIT 1";

        $db = self::getInstance();
        $req = $db->prepare($sql);

        if ($req->execute($var)) {
            $res = $req->fetch();
        }

        //Creating the dates
        $dateLatest = strtotime($res['date']); // Last vote date
        $nextVoteDate = $dateLatest + ($time * 60);

        //Converting the dates
        $dateLatest = date("Y-m-d h:i:s", $dateLatest);
        $nextVoteDate = date("Y-m-d h:i:s", $nextVoteDate);
        $currentDate = date("Y-m-d h:i:s", $currentDate);

        //Check if the player has already vote or not
        if ($currentDate >= $nextVoteDate || $currentDate === $dateLatest) {
            $this->storeVote();
            return "GOOD";
        } else {
            return "ALREADY_VOTE";
        }


    }

    public function storeVote(): void
    {
        $var = array(
            "id_user" => $this->idUser,
            "ip" => $this->ipPlayer,
            "id_site" => $this->idSite
        );

        $sql = "INSERT INTO cmw_votes_votes (votes_id_user, votes_ip, votes_id_site) VALUES (:id_user, :ip, :id_site)";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        $req->execute($var);
    }

    //Return true if the player can vote

    public function check($url)
    {

        //List of all websites:
        if (strpos($url, 'serveur-prive.net')) {
            $result = @file_get_contents("https://serveur-prive.net/api/vote/json/$this->idUnique/$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))) {
                if ($result === false || intval($result['status']) == 1) {
                    return true;
                }
            }
        } elseif (strpos($url, 'serveur-minecraft-vote.fr')) {
            $result = @file_get_contents("https://serveur-minecraft-vote.fr/api/v1/servers/$this->idUnique/vote/$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))) {
                if ($result['canVote'] === false) {
                    return true;
                }
            }
        } elseif (strpos($url, 'serveurs-mc.net')) {
            $result = @file_get_contents("https://serveurs-mc.net/api/hasVote/$this->idUnique/$this->ipPlayer/10");
            if ($result && ($result = json_decode($result, true))) {
                if ($result['hasVote'] === false) {
                    return true;
                }
            }
        } elseif (strpos($url, 'top-serveurs.net')) {
            $result = @file_get_contents("https://api.top-serveurs.net/v1/votes/check-ip?server_token=$this->idUnique&ip=$this->ipPlayer");
            if ($result && ($result = json_decode($result, true))) {
                return true;
            }
        } elseif (strpos($url, 'serveursminecraft.org')) {
            $result = @file_get_contents("https://www.serveursminecraft.org/sm_api/peutVoter.php?id=$this->idUnique&ip=$this->ipPlayer");
            if ($result !== "true") {
                return true;
            }
        } elseif (strpos($url, 'liste-serveurs-minecraft.org')) {
            $result = @file_get_contents("https://api.liste-serveurs-minecraft.org/vote/vote_verification.php?server_id=$this->idUnique&ip=$this->ipPlayer");
            if ($result == 1) {
                return true;
            }
        } elseif (strpos($url, 'serveur-minecraft.com')) {
            $result = @file_get_contents("https://serveur-minecraft.com/api/1/vote/$this->idUnique/$this->ipPlayer");
            if ($result == 0) {
                return true;
            }
        }

    }

}
