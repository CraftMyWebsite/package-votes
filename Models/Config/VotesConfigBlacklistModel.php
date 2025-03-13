<?php

namespace CMW\Model\Votes\Config;

use CMW\Entity\Votes\Config\VotesConfigBlacklistEntity;
use CMW\Manager\Database\DatabaseManager;
use CMW\Manager\Package\AbstractModel;
use ReflectionException;

/**
 * Class: @VotesConfigBlacklistModel
 * @package Votes
 * @link https://craftmywebsite.fr/docs/fr/technical/creer-un-package/models
 */
class VotesConfigBlacklistModel extends AbstractModel
{
    /**
     * @return VotesConfigBlacklistEntity[]
     */
    public function getBlacklists(): array
    {
        $sql = 'SELECT ranking.user_id, ranking.author_id, ranking.created_at, user.user_pseudo, 
                    author.user_pseudo as author_pseudo
                    FROM cmw_votes_ignored_ranking ranking
                    JOIN cmw_users user on ranking.user_id = user.user_id
                    JOIN cmw_users author on ranking.author_id = author.user_id';
        $db = DatabaseManager::getInstance();

        $req = $db->prepare($sql);

        if (!$req->execute()) {
            return [];
        }

        $res = $req->fetchAll();

        if (!$res) {
            return [];
        }

        try {
            return VotesConfigBlacklistEntity::toEntityList($res);
        } catch (ReflectionException $_) {
            return [];
        }
    }

    /**
     * @param int $userId
     * @param int $authorId
     * @return bool
     */
    public function addBlacklist(int $userId, int $authorId): bool
    {
        $data = [
            'user_id' => $userId,
            'author_id' => $authorId,
        ];

        $sql = "INSERT INTO cmw_votes_ignored_ranking (user_id, author_id) VALUES (:user_id, :author_id)";
        $db = DatabaseManager::getInstance();

        return $db->prepare($sql)->execute($data);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function removeBlacklist(int $userId): bool
    {
        $sql = "DELETE FROM cmw_votes_ignored_ranking WHERE user_id = :user_id";
        $db = DatabaseManager::getInstance();
        return $db->prepare($sql)->execute(['user_id' => $userId]);
    }
}
