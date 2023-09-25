<?php

namespace CMW\Model\Votes;


use CMW\Entity\Votes\VotesConfigEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;


/**
 * Class @VotesConfigModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesConfigModel extends AbstractModel
{
    public function updateConfig(int $topShow, int $reset, int $autoTopRewardActive, string $autoTopReward, bool $enableApi): ?VotesConfigEntity
    {
        $info = [
            "top_show" => $topShow,
            "reset" => $reset,
            "auto_top_reward_active" => $autoTopRewardActive,
            "auto_top_reward" => $autoTopReward,
            "enable_api" => $enableApi,
        ];

        $sql = "UPDATE cmw_votes_config SET votes_config_top_show=:top_show, votes_config_reset=:reset,
                            votes_config_auto_top_reward_active=:auto_top_reward_active, 
                            votes_config_auto_top_reward=:auto_top_reward, votes_config_enable_api = :enable_api";

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getConfig();
        }

        return null;
    }

    public function getConfig(): ?VotesConfigEntity
    {
        $sql = "SELECT * FROM cmw_votes_config LIMIT 1";

        $db = DatabaseManager::getInstance();
        $res = $db->prepare($sql);


        if (!$res->execute()) {
            return null;
        }

        $res = $res->fetch();

        return new VotesConfigEntity(
            $res['votes_config_top_show'],
            $res['votes_config_reset'],
            $res['votes_config_auto_top_reward_active'],
            $res['votes_config_auto_top_reward'],
            $res['votes_config_enable_api'],
            $res['votes_config_need_login']
        );
    }
}