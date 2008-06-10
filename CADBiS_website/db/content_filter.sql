DROP TABLE IF EXISTS `url_categories_denied`;
CREATE TABLE  `url_categories_denied` (
  `ucdid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ucdid`),
  KEY `FK_url_categories_denied_url_categories` (`cid`),
  CONSTRAINT `FK_url_categories_denied_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `url_categories_denied_log`;
CREATE TABLE  `url_categories_denied_log` (
  `ucdlid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ucdlid`),
  KEY `Index_gid` (`gid`),
  KEY `FK_url_categories_denied_log_categories` (`cid`),
  KEY `Index_uid` (`uid`),
  CONSTRAINT `FK_url_categories_denied_log_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `url_categories_keywords`;
CREATE TABLE  `url_categories_keywords` (
  `uckid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `keyword` varchar(20) NOT NULL,
  PRIMARY KEY (`uckid`),
  UNIQUE KEY `Index_keyword` (`keyword`),
  KEY `FK_url_categories_keywords_url_categories` (`cid`),
  CONSTRAINT `FK_url_categories_keywords_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `url_categories_match`;
CREATE TABLE  `url_categories_match` (
  `u2cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`u2cid`),
  KEY `url_index` (`url`),
  KEY `FK_url2category_url_categories` (`cid`),
  CONSTRAINT `FK_url2category_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `url_categories_unsensewords`;
CREATE TABLE  `url_categories_unsensewords` (
  `ucuwid` int(10) unsigned NOT NULL auto_increment,
  `keyword` varchar(20) NOT NULL,
  PRIMARY KEY  (`ucuwid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `url_categories_match` ADD UNIQUE INDEX `Index_url_unique`(`url`);
ALTER TABLE `url_categories_unsensewords` ADD UNIQUE `Index_keyword_unique`(`keyword`), ENGINE = InnoDB;
ALTER TABLE `url_categories_denied_log` ADD COLUMN `url` VARCHAR(125) NOT NULL AFTER `uid`, ENGINE = InnoDB;

ALTER TABLE `url_categories_denied_log` CHANGE COLUMN `gid` `unique_id` VARCHAR(64) NOT NULL,
 DROP COLUMN `uid`
, DROP INDEX `Index_gid`
, DROP INDEX `Index_uid`
, ENGINE = InnoDB;

ALTER TABLE `url_categories_keywords` MODIFY COLUMN `keyword` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
, ENGINE = InnoDB;
ALTER TABLE `url_categories_unsensewords` MODIFY COLUMN `keyword` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
, ENGINE = InnoDB;


CREATE TABLE `cadbis_tmp` (
  `key` VARCHAR(64) NOT NULL,
  `value` BIGINT UNSIGNED NOT NULL
)
ENGINE = MEMORY;

ALTER TABLE `cadbis_tmp` ADD INDEX `Index_key`(`key`)
, ENGINE = MEMORY;


ALTER TABLE `cadbis_tmp` DROP INDEX `Index_key`,
 ADD UNIQUE `Index_key` USING HASH(`key`)
, ENGINE = MEMORY;


insert into `cadbis_tmp` values('current_memory_usage',0);
insert into `cadbis_tmp` values('current_channel_loading',0);

ALTER TABLE `cadbis_tmp` CHANGE COLUMN `key` `ckey` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
 CHANGE COLUMN `value` `cvalue` BIGINT(20) UNSIGNED NOT NULL,
 ADD PRIMARY KEY (`ckey`),
 DROP INDEX `Index_key`,
 ADD UNIQUE INDEX `Index_key` USING HASH(`ckey`);

