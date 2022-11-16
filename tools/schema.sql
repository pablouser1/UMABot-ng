-- umabot.pins definition
CREATE TABLE `pins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `niu` varchar(16) NOT NULL,
  `pin` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pins_UN` (`user_id`),
  UNIQUE KEY `pins_UN2` (`niu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- umabot.users definition
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `niu` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_UN` (`user_id`),
  UNIQUE KEY `users_UN2` (`niu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- umabot.contents definition
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(8) NOT NULL,
  `msg` mediumtext DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `media_url` varchar(100) DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- umabot.admins definition
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` binary(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;