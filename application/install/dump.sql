--
-- База данных: `student`
--

-- Таблица меток на кате
CREATE TABLE IF NOT EXISTS `prefix_maps` (
  `id` 	 INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `target_type`	 TINYINT UNSIGNED NOT NULL,
	`target_id`	 INT UNSIGNED NOT NULL,
  `lat`	 FLOAT(10,6) NOT NULL,
	`lng`	 FLOAT(10,6) NOT NULL,
	`title`  VARCHAR(500) NOT NULL,
	`phone`  VARCHAR(20) NOT NULL,
	`description`  VARCHAR(500) NOT NULL,
	`address`  		 VARCHAR(500) NOT NULL,
  `date_add`     DATETIME NOT NULL,
  `date_edit`   DATETIME DEFAULT NULL,
  PRIMARY KEY  (`id`),
  INDEX `target_id_target_type_lat_lng` 							(`target_id`, `target_type`, `lat`, `lng`)
)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- Уведомления
CREATE TABLE IF NOT EXISTS `prefix_notification` (
  `id`                  int(11) unsigned NOT NULL AUTO_INCREMENT,
	`target_type`			 		VARCHAR (50) NOT NULL DEFAULT '',
	`target_subtype`			VARCHAR (50) NOT NULL DEFAULT '',
  `target_id` 					INT UNSIGNED NOT NULL,
  `target_subid` 				INT UNSIGNED NOT NULL,


	`sender_id` 					INT(11) UNSIGNED NOT NULL,
	`recipient_id` 				INT(11) UNSIGNED NOT NULL,

  `meta`		            TEXT NOT NULL,
  `data`		            TEXT NOT NULL,
  `is_anonymous`        tinyint(3) NOT NULL default '0',
  `is_new` tinyint(2)   NOT NULL default '1',
  `view_date` 	        DATETIME NOT NULL,
	`add_date` 		        DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Таблица наших институтов
CREATE TABLE IF NOT EXISTS `prefix_instituts` (
	`id` 						INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`number` 				INT(11) UNSIGNED NOT NULL,
	`name`			 		VARCHAR (40) NOT NULL,
	`adress`			 	VARCHAR (255) NOT NULL,
	`description`			 		TEXT,
  PRIMARY KEY (`id`),
  KEY `number` (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Таблица наших групп
CREATE TABLE IF NOT EXISTS `prefix_group` (
	`id` 						 INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name`			 		 VARCHAR (40) NOT NULL,
	`name_full`			 VARCHAR (240) NOT NULL,
	`tutor_id` 			 INT(11) UNSIGNED NOT NULL,
	`institut_id` 	 INT(11) UNSIGNED NOT NULL,

  PRIMARY KEY (`id`),
  KEY `tutor_id` (`tutor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------



-- Запоминает авторизацию пользователя
CREATE TABLE IF NOT EXISTS `prefix_reminder` (
  `code` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_used` datetime DEFAULT '0000-00-00 00:00:00',
  `date_expire` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- сессии
CREATE TABLE IF NOT EXISTS `prefix_session` (
  `key` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_create` varchar(40) NOT NULL,
  `ip_last` varchar(40) NOT NULL,
  `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last` datetime NOT NULL,
  `date_close` datetime DEFAULT NULL,
  `extra` text,
  PRIMARY KEY (`key`),
  KEY `date_last` (`date_last`),
  KEY `date_close` (`date_close`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Инвайты для регистрации
CREATE TABLE IF NOT EXISTS `prefix_invite_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned DEFAULT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `parent_name` varchar(40) NOT NULL,
  `date_birthday` datetime NOT NULL,
  `card_number` varchar(16) NOT NULL,

  `code` varchar(32) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_expired` datetime DEFAULT NULL,

  `count_allow_use` int(11) NOT NULL DEFAULT '1',
  `count_use` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `count_allow_use` (`count_allow_use`),
  KEY `count_use` (`count_use`),
  KEY `active` (`active`),
  KEY `date_create` (`date_create`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- использованые инвайты
CREATE TABLE IF NOT EXISTS `prefix_invite_use` (
  `id`  int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `code_id` int(11) unsigned DEFAULT NULL,
  `from_user_id` int(11) unsigned DEFAULT NULL,
  `to_user_id` int(11) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `code_id` (`code_id`),
  KEY `from_user_id` (`from_user_id`),
  KEY `to_user_id` (`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Наши пользователи
CREATE TABLE IF NOT EXISTS `prefix_user` (
  `id` int(11)  unsigned NOT NULL AUTO_INCREMENT,
  `type` TINYINT NOT NULL DEFAULT 0, -- На будующее тип
  `mail` varchar(50) NOT NULL, -- почта
  `password` varchar(32) NOT NULL, -- пароли закодированный
  `group_id`  INT(11) UNSIGNED NOT NULL, -- группа
  `card_number` varchar(16) NOT NULL, -- номер зачетной книги
  `card_id` INT(11) UNSIGNED NOT NULL, -- ссылка на карту

  `first_name` varchar(40) NOT NULL, -- Имя
  `last_name` varchar(40) NOT NULL, -- Фамилия
  `parent_name` varchar(40) NOT NULL,  -- Отчество

  `date_birthday` datetime NOT NULL, -- Дата рождения
  `avatar` varchar(250) DEFAULT NULL,  -- Ссылка на аватар
	`phone`		VARCHAR (20) NOT NULL, -- телефон

  `date_create` datetime NOT NULL,
  `date_activate` datetime DEFAULT NULL,

  `ip_create` varchar(40) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',  --
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',  -- флаг админа
  `activate` tinyint(1) NOT NULL DEFAULT '0',  -- флаг прохождения активации
  `activate_key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mail` (`mail`),
  KEY `password` (`password`),
  KEY `activate_key` (`activate_key`),
  KEY `active` (`active`),
  KEY `activate` (`activate`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;