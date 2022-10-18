<?php

namespace cmw\Model\Votes;


use CMW\Entity\Votes\VotesConfigEntity;
use CMW\Manager\Database\DatabaseManager;


/**
 * Class @ConfigModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class ConfigModel extends DatabaseManager
{
    //Config



    //Get the config
    public function getConfig(): ?VotesConfigEntity
    {

        $sql = "SELECT * FROM cmw_votes_config LIMIT 1";

        $db = self::getInstance();
        $res = $db->prepare($sql);


        if (!$res->execute()) {
            return null;
        }

        $res = $res->fetch();

        return new VotesConfigEntity(
            $res['votes_config_top_show'],
            $res['votes_config_reset'],
            $res['votes_config_auto_top_reward_active'],
            $res['votes_config_auto_top_reward']
        );
    }

    //Update the config
    public function updateConfig(int $topShow, int $reset, int $autoTopRewardActive, string $autoTopReward): ?VotesConfigEntity
    {
        $info = array(
            "top_show" => $topShow,
            "reset" => $reset,
            "auto_top_reward_active" => $autoTopRewardActive,
            "auto_top_reward" => $autoTopReward
        );

        $sql = "UPDATE cmw_votes_config SET votes_config_top_show=:top_show, votes_config_reset=:reset, 
                            votes_config_auto_top_reward_active=:auto_top_reward_active, 
                            votes_config_auto_top_reward=:auto_top_reward";

        $db = self::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getConfig();
        }

        return null;
    }
}