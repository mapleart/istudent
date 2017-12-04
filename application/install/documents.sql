-- Схема документов
CREATE TABLE `prefix_document_scheme` (
	`id` 																												INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` 																											VARCHAR (100) NOT NULL DEFAULT '',
	`description` 																							VARCHAR (2000) NOT NULL DEFAULT '',
	`sorting` 																									INT NOT NULL DEFAULT 1,
	PRIMARY KEY 																								(`id`),
	INDEX `sorting` 																						(`sorting` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- Таблица с привязанными полями
CREATE TABLE `prefix_document_scheme_field` (
	`id` 																		INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`document_scheme_id` 										INT UNSIGNED NOT NULL,
	`title` 																VARCHAR (500) NOT NULL DEFAULT '',
	`description` 													VARCHAR (1000) NOT NULL DEFAULT '',
	`mandatory` 														TINYINT NOT NULL DEFAULT 1,
	`code` 														      VARCHAR (50) NOT NULL,
	`field_type` 														VARCHAR (50) NOT NULL,
	`sorting` 															INT NOT NULL DEFAULT 1,
	PRIMARY KEY 														(`id`),
	INDEX `scheme_id_sorting` 							(`document_scheme_id`, `sorting` ASC)

) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci;

-- Для выпадающего списка пункты
CREATE TABLE `prefix_document_scheme_field_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `field_id` int(11) NOT NULL,
  `sorting` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Схема документов
CREATE TABLE `prefix_document` (
	`id` 								    INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` 						  INT(11) UNSIGNED NOT NULL,
	`document_scheme_id` 		INT(11) UNSIGNED NOT NULL,
	`send_mail` 					  TINYINT NOT NULL DEFAULT 0,
	`status`			 				  TINYINT NOT NULL,
	`add_date` 						  DATETIME NOT NULL,
	`action_date` 					DATETIME NOT NULL,

	PRIMARY KEY 																								(`id`),
	INDEX `user_id` 																						(`user_id` ASC)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `prefix_document_value` (
	`id` 								        INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` 						      INT(11) UNSIGNED NOT NULL,
	`document_id` 		          INT(11) UNSIGNED NOT NULL,
	`document_scheme_id` 		          INT(11) UNSIGNED NOT NULL,
	`document_scheme_field_id` 	      INT(11) UNSIGNED NOT NULL,
	`document_scheme_field_value_id` 	INT(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(32) NOT NULL,
	PRIMARY KEY 																								(`id`),
	INDEX `user_id` 																						(`user_id` ASC)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
