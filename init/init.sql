CREATE TABLE IF NOT EXISTS `cmw_votes_sites`
(
    `votes_sites_id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `votes_sites_title`       VARCHAR(255) NOT NULL,
    `votes_sites_url`         VARCHAR(255) NOT NULL,
    `votes_sites_time`        INT(10)      NOT NULL,
    `votes_sites_id_unique`   VARCHAR(255) NOT NULL,
    `votes_sites_rewards_id`  INT(11)      NULL,
    `votes_sites_date_create` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`votes_sites_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cmw_votes_votes`
(
    `votes_id`      int(11)     NOT NULL,
    `votes_id_user` int(11)     NOT NULL,
    `votes_ip`      varchar(39) NOT NULL,
    `votes_id_site` int(11)     NULL,
    `votes_date`    timestamp   NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cmw_votes_config`
(
    `votes_config_top_show`               INT(10)    NOT NULL DEFAULT '10',
    `votes_config_reset`                  INT(1)     NOT NULL DEFAULT '1' COMMENT '1 = reset tous les mois\r\n0 = pas de reset mensuel',
    `votes_config_auto_top_reward_active` INT(1)     NULL     DEFAULT '0' COMMENT '0 = pas de récompenses automatiques\r\n1 = récompenses automatiques activés',
    `votes_config_auto_top_reward`        TEXT       NULL COMMENT 'Récompenses automatiques pour les x premiers (JSON)',
    `votes_config_enable_api`             TINYINT(1) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `cmw_votes_rewards`
(
    `votes_rewards_rewards_id` INT(11)      NOT NULL,
    `votes_rewards_title`      VARCHAR(255) NOT NULL,
    `votes_rewards_action`     TEXT         NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cmw_votes_logs_rewards`
(
    `votes_logs_rewards_id`        int(10)   NOT NULL,
    `votes_logs_rewards_user_id`   int(11)   NULL,
    `votes_logs_rewards_reward_id` int(11)   NULL     DEFAULT NULL,
    `votes_logs_rewards_date`      timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `cmw_votes_votepoints`
(
    `votes_votepoints_id`      int(11) NOT NULL,
    `votes_votepoints_id_user` int(11) NOT NULL,
    `votes_votepoints_amount`  int(11) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

ALTER TABLE `cmw_votes_rewards`
    ADD PRIMARY KEY (`votes_rewards_rewards_id`),
    ADD UNIQUE KEY `rewards_id` (`votes_rewards_rewards_id`);

ALTER TABLE `cmw_votes_rewards`
    MODIFY `votes_rewards_rewards_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cmw_votes_sites`
    ADD KEY `rewards_id` (`votes_sites_rewards_id`);

ALTER TABLE `cmw_votes_sites`
    ADD CONSTRAINT `fk_cmw_votes_rewards` FOREIGN KEY (`votes_sites_rewards_id`)
        REFERENCES `cmw_votes_rewards` (`votes_rewards_rewards_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `cmw_votes_votes`
    ADD PRIMARY KEY (`votes_id`),
    ADD KEY `id_user` (`votes_id_user`),
    ADD KEY `id_site` (`votes_id_site`);

ALTER TABLE `cmw_votes_votes`
    MODIFY `votes_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cmw_votes_votes`
    ADD CONSTRAINT `cmw_votes_votes_ibfk_1` FOREIGN KEY (`votes_id_user`)
        REFERENCES `cmw_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `cmw_votes_votes_ibfk_2` FOREIGN KEY (`votes_id_site`)
        REFERENCES `cmw_votes_sites` (`votes_sites_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

ALTER TABLE `cmw_votes_logs_rewards`
    ADD PRIMARY KEY (`votes_logs_rewards_id`),
    ADD KEY `reward_id` (`votes_logs_rewards_reward_id`),
    ADD KEY `user_id` (`votes_logs_rewards_user_id`);

ALTER TABLE `cmw_votes_logs_rewards`
    MODIFY `votes_logs_rewards_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cmw_votes_logs_rewards`
    ADD CONSTRAINT `cmw_votes_logs_rewards_ibfk_1` FOREIGN KEY (`votes_logs_rewards_reward_id`)
        REFERENCES `cmw_votes_rewards` (`votes_rewards_rewards_id`) ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `cmw_votes_logs_rewards_ibfk_2` FOREIGN KEY (`votes_logs_rewards_user_id`)
        REFERENCES `cmw_users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

ALTER TABLE `cmw_votes_votepoints`
    ADD PRIMARY KEY (`votes_votepoints_id`),
    ADD UNIQUE KEY `id_user` (`votes_votepoints_id_user`);

ALTER TABLE `cmw_votes_votepoints`
    MODIFY `votes_votepoints_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cmw_votes_votepoints`
    ADD CONSTRAINT `cmw_votes_votepoints_ibfk_1` FOREIGN KEY (`votes_votepoints_id_user`)
        REFERENCES `cmw_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


#Generate default config
INSERT INTO `cmw_votes_config` (`votes_config_top_show`, `votes_config_reset`, `votes_config_auto_top_reward_active`,
                                `votes_config_auto_top_reward`, `votes_config_enable_api`)
VALUES ('10', '1', '0', NULL, 1);

