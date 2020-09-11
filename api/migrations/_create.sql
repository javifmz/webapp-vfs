
-- Migrations
CREATE TABLE IF NOT EXISTS `migration` (
  `id` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Basic user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userEmailUnique` (`email`),
  UNIQUE KEY `userUsernameUnique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;