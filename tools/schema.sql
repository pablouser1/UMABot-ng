-- umabot.admins definition
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` binary(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- umabot.contents definition
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(16) NOT NULL,
  `msg` mediumtext DEFAULT NULL,
  `media_id` varchar(100) DEFAULT NULL,
  `media_url` mediumtext DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'text',
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- umabot.pins definition
CREATE TABLE `pins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `niu` varchar(100) NOT NULL,
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
