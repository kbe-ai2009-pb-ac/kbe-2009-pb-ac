DROP TABLE IF EXISTS wcf1_seo;
CREATE TABLE wcf1_seo (
	`classFile` VARCHAR(255) NOT NULL DEFAULT '',
	`className` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY  (classFile)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;