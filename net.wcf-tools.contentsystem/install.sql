DROP TABLE IF EXISTS wcf1_content;
CREATE TABLE wcf1_content (
	`contentID` INT(10) UNSIGNED NOT NULL auto_increment,
	`parentID` INT(11) NOT NULL,
	`contentType` INT(1) NOT NULL,
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`content` TEXT NOT NULL,
	`url` VARCHAR(255) NOT NULL,
	`active` INT(1) UNSIGNED NOT NULL,
	`invisible` INT(1) UNSIGNED NOT NULL,
	`releaseDate` INT(11) NOT NULL,
	`username` VARCHAR(255) NOT NULL DEFAULT '',
	`lastChangedDate` INT(11) NOT NULL,
	PRIMARY KEY  (contentID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_content_structure;
CREATE TABLE wcf1_content_structure (
	`contentID` INT(10) NOT NULL,
	`parentID` INT(11) NOT NULL,
	`position` INT(11) NOT NULL,
	PRIMARY KEY  (contentID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;