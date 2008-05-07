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
  KEY `Index_gid` (`gid`) USING BTREE,
  KEY `FK_url_categories_denied_log_categories` (`cid`),
  KEY `Index_uid` (`uid`) USING BTREE,
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
  KEY `url_index` (`url`) USING BTREE,
  KEY `FK_url2category_url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;