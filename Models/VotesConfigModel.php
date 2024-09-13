<?php

namespace CMW\Model\Votes;

use CMW\Entity\Votes\VotesConfigEntity;
use CMW\Manager\Cache\SimpleCacheManager;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use function is_null;

/**
 * Class @VotesConfigModel
 * @package votes
 * @author Teyir
 * @version 1.0
 */
class VotesConfigModel extends AbstractModel
{
    public function updateConfig(
        int    $topShow,
        int    $reset,
        int    $autoTopRewardActive,
        string $autoTopReward,
        int    $enableApi,
        int    $needLogin,
    ): ?VotesConfigEntity
    {
        $info = [
            'top_show' => $topShow,
            'reset' => $reset,
            'auto_top_reward_active' => $autoTopRewardActive,
            'auto_top_reward' => $autoTopReward,
            'enable_api' => $enableApi,
            'need_login' => $needLogin,
        ];

        $sql = 'UPDATE cmw_votes_config SET votes_config_top_show=:top_show, votes_config_reset=:reset,
                            votes_config_auto_top_reward_active=:auto_top_reward_active, 
                            votes_config_auto_top_reward=:auto_top_reward, votes_config_enable_api = :enable_api, 
                            votes_config_need_login = :need_login';

        $db = DatabaseManager::getInstance();
        $req = $db->prepare($sql);
        if ($req->execute($info)) {
            return $this->getConfig(ignoreCache: true);
        }

        return null;
    }

    public function getConfig(bool $ignoreCache = false): ?VotesConfigEntity
    {
       if (!$ignoreCache){
           $cacheData = SimpleCacheManager::getCache('config', 'Votes');

           if (!is_null($cacheData)) {
               return VotesConfigEntity::fromJson($cacheData);
           }
       }

        $sql = 'SELECT * FROM cmw_votes_config LIMIT 1';

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
