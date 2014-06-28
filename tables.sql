CREATE TABLE `events` (
  `id` varchar(34) NOT NULL,
  `owner` varchar(10) DEFAULT NULL,
  `title` text NOT NULL,
  `details` text NOT NULL,
  `time` datetime NOT NULL,
  `cover` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `users` (
  `id` varchar(10) NOT NULL,
  `event_id` varchar(34) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` char(1) NOT NULL,
  `status_changed` datetime NOT NULL,
  `visited` datetime NOT NULL,
  PRIMARY KEY (`id`,`event_id`)
);

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` varchar(34) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `type` char(1) NOT NULL,
  `data` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
);

