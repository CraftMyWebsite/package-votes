<?php

namespace CMW\Entity\Votes\Config;

use CMW\Entity\Users\UserEntity;
use CMW\Manager\Package\AbstractEntity;
use CMW\Model\Users\UsersModel;
use CMW\Utils\Date;

/**
 * Class: @VotesConfigBlacklistEntity
 * @package Votes
 */
class VotesConfigBlacklistEntity extends AbstractEntity
{
    private int $userId;
    private int $authorId;
    private string $createdAt;
    private string $userPseudo;
    private string $authorPseudo;

    /**
     * @param int $userId
     * @param int $authorId
     * @param string $createdAt
     * @param string $userPseudo
     * @param string $authorPseudo
     */
    public function __construct(int $userId, int $authorId, string $createdAt, string $userPseudo, string $authorPseudo)
    {
        $this->userId = $userId;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->userPseudo = $userPseudo;
        $this->authorPseudo = $authorPseudo;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedAtFormatted(): string
    {
        return Date::formatDate($this->createdAt);
    }

    /**
     * @return string
     */
    public function getUserPseudo(): string
    {
        return $this->userPseudo;
    }

    /**
     * @return string
     */
    public function getAuthorPseudo(): string
    {
        return $this->authorPseudo;
    }

    /**
     * @return UserEntity|null
     */
    public function getUser(): ?UserEntity
    {
        return UsersModel::getInstance()->getUserById($this->userId);
    }

    /**
     * @return UserEntity|null
     */
    public function getAuthor(): ?UserEntity
    {
        return UsersModel::getInstance()->getUserById($this->authorId);
    }
}
