-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 31 Août 2011 à 13:05
-- Version du serveur: 5.1.54
-- Version de PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `devv1-phpgasus`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_logs`
--

CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) DEFAULT NULL,
  `action` enum('create','update','delete','import') NOT NULL,
  `resource_name` varchar(32) NOT NULL,
  `resource_id` varchar(32) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `revert_query` text,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `admin_logs`
--


-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `slug` varchar(32) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `admin_title` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `groups`
--

INSERT INTO `groups` (`id`, `name`, `slug`, `creation_date`, `update_date`) VALUES
(1, 'users', 'users', '0000-00-00 00:00:00', '2010-11-26 17:59:01'),
(2, 'gods', 'gods', '0000-00-00 00:00:00', '2010-11-26 17:59:11'),
(3, 'superadmins', 'superadmins', '0000-00-00 00:00:00', '2010-11-26 17:59:20'),
(4, 'admins', 'admins', '0000-00-00 00:00:00', '2010-11-26 17:59:26'),
(5, 'contributors', 'contributors', '0000-00-00 00:00:00', '2010-11-26 17:59:39'),
(6, 'moderators', 'moderators', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `groups_auths`
--

CREATE TABLE IF NOT EXISTS `groups_auths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `allow_display` tinyint(1) NOT NULL DEFAULT '1',
  `allow_create` tinyint(1) NOT NULL DEFAULT '0',
  `allow_retrieve` tinyint(1) NOT NULL DEFAULT '0',
  `allow_update` tinyint(1) NOT NULL DEFAULT '0',
  `allow_delete` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_auth_unique` (`group_id`,`resource_id`),
  KEY `group_id` (`group_id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='groups authorisations (ACL)' AUTO_INCREMENT=1 ;

--
-- Contenu de la table `groups_auths`
--


-- --------------------------------------------------------

--
-- Structure de la table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `singular` varchar(32) NOT NULL,
  `plural` varchar(32) NOT NULL,
  `type` enum('native','filter','relation') NOT NULL DEFAULT 'native',
  `table` varchar(32) DEFAULT NULL,
  `alias` varchar(8) DEFAULT NULL,
  `extends` varchar(32) DEFAULT NULL,
  `display_name` varchar(32) DEFAULT NULL,
  `name_field` varchar(32) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `resources`
--

INSERT INTO `resources` (`id`, `name`, `singular`, `plural`, `type`, `table`, `alias`, `extends`, `display_name`, `name_field`, `creation_date`, `update_date`) VALUES
(1, 'adminlogs', 'adminlog', 'adminlogs', 'native', 'admin_logs', 'admlogs', NULL, 'admin logs', 'slug', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'resources', 'resource', 'resources', 'native', 'phpg_resources', '_r', NULL, 'resources', 'name', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `resources_columns`
--

CREATE TABLE IF NOT EXISTS `resources_columns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` enum('string','email','password','url','tel','color','meta','ip','slug','tag','text','html','code','int','tinyint','float','smallint','mediumint','bigint','bool','boolean','timestamp','datetime','date','time','year','month','week','day','hour','minutes','seconds','onetoone','onetomany','manytoone','manytomany','id','enum','file','image','video','sound') NOT NULL,
  `realtype` enum('serial','bit','tinyint','bool','smallint','mediumint','int','bigint','float','double','double precision','decimal','date','datetime','timestamp','time','year','char','varchar','binary','varbinary','tinyblob','tinytext','blob','text','mediumblob','mediumtext','longblob','longtext','enum','set') NOT NULL,
  `length` bigint(20) NOT NULL,
  `pk` tinyint(1) NOT NULL DEFAULT '0',
  `ai` tinyint(1) NOT NULL DEFAULT '0',
  `fk` tinyint(1) NOT NULL DEFAULT '0',
  `default` varchar(255) NOT NULL,
  `null` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `resources_columns`
--


-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `expiration_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(48) NOT NULL,
  `last_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `sessions`
--


-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(64) DEFAULT NULL,
  `type` enum('import') DEFAULT NULL,
  `subtype` varchar(32) DEFAULT NULL,
  `items_count` int(8) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `tasks`
--


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `device_id` varchar(64) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_key` varchar(32) NOT NULL,
  `password_reset_key` varchar(32) NOT NULL,
  `private_key` varchar(16) NOT NULL,
  `creation_date` timestamp NULL DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `name`, `device_id`, `activated`, `activation_key`, `password_reset_key`, `private_key`, `creation_date`, `update_date`) VALUES
(1, 'nobody@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', 'john', 'doe', '', '', 0, '', '', '', '2010-12-02 11:15:23', '2011-06-28 11:11:06'),
(2, 'administrator@anonymous.com', 'f845fb444033f19b8568373351b868dd5b4e54af', 'Ad', 'Ministrator', 'Ad Ministrator', '', 1, '', '', '', '2011-06-28 11:01:02', '2011-06-28 11:11:09');

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_id_2` (`user_id`,`group_id`),
  KEY `users_id` (`user_id`),
  KEY `groups_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `creation_date`, `update_date`) VALUES
(1, 2, 2, '0000-00-00 00:00:00', '2011-08-31 11:03:05');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `groups_auths`
--
ALTER TABLE `groups_auths`
  ADD CONSTRAINT `groups_auths_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_auths_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `resources_columns`
--
ALTER TABLE `resources_columns`
  ADD CONSTRAINT `resources_columns_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
