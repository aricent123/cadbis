-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema nibs
--

CREATE DATABASE IF NOT EXISTS nibs;
USE nibs;

--
-- Definition of table `url_categories`
--

DROP TABLE IF EXISTS `url_categories`;
CREATE TABLE `url_categories` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories`
--

/*!40000 ALTER TABLE `url_categories` DISABLE KEYS */;
INSERT INTO `url_categories` (`cid`,`title`) VALUES 
 (0,'Unknown'),
 (1,'Pornography'),
 (2,'Erotic / Sex'),
 (3,'Swimwear / Lingerie'),
 (4,'Shopping'),
 (5,'Auctions / Classified Ads'),
 (6,'Governmental Organizations'),
 (7,'Non-Governmental Organizations'),
 (8,'Cities / Regions / Countries'),
 (9,'Education'),
 (10,'Political Parties'),
 (11,'Religion'),
 (12,'Sects'),
 (13,'Illegal Activities'),
 (14,'Computer Crime'),
 (15,'Political Extreme / Hate / Discrimination'),
 (16,'Warez / Hacking / Illegal Software'),
 (17,'Violence / Extreme'),
 (18,'Gambling / Lottery'),
 (19,'Computer Games'),
 (20,'Toys'),
 (21,'Cinema / Television'),
 (22,'Recreational Facilities / Amusement / Theme Parks'),
 (23,'Art / Museums / Memorials / Monuments'),
 (24,'Music'),
 (25,'Literature / Books'),
 (26,'Humor / Comics'),
 (27,'General News / Newspapers / Magazines'),
 (28,'Web Mail'),
 (29,'Chat'),
 (30,'Newsgroups / Bulletin Boards / Blogs'),
 (31,'Mobile Telephony'),
 (32,'Digital Postcards'),
 (33,'Search Engines / Web Catalogs / Portals'),
 (34,'Software / Hardware / Distributors'),
 (35,'Communication Services'),
 (36,'IT Security / IT Information'),
 (37,'Website Translation'),
 (38,'Anonymous Proxies'),
 (39,'Illegal Drugs'),
 (40,'Alcohol'),
 (41,'Tobacco'),
 (42,'Self-Help / Addiction'),
 (43,'Dating / Relationships'),
 (44,'Restaurants / Bars'),
 (45,'Travel'),
 (46,'Fashion / Cosmetics / Jewelry'),
 (47,'Sports'),
 (48,'Building / Residence / Architecture / Furniture'),
 (49,'Nature / Environment / Animals'),
 (50,'Personal Homepages'),
 (51,'Job Search'),
 (52,'Investment Brokers / Stocks'),
 (53,'Financial Services / Investment / Insurance'),
 (54,'Banking / Home Banking'),
 (55,'Vehicles / Transportation'),
 (56,'Weapons / Military'),
 (57,'Health'),
 (58,'Abortion'),
 (61,'Malware'),
 (63,'Instant Messaging');
/*!40000 ALTER TABLE `url_categories` ENABLE KEYS */;


--
-- Definition of table `url_categories_conflict_words`
--

DROP TABLE IF EXISTS `url_categories_conflict_words`;
CREATE TABLE `url_categories_conflict_words` (
  `uccwid` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(125) NOT NULL,
  `word` varchar(125) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`uccwid`),
  KEY `Index_url` (`url`),
  KEY `FK_New Table_url_categories` (`cid`),
  CONSTRAINT `FK_New Table_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_conflict_words`
--

/*!40000 ALTER TABLE `url_categories_conflict_words` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_categories_conflict_words` ENABLE KEYS */;


--
-- Definition of table `url_categories_denied`
--

DROP TABLE IF EXISTS `url_categories_denied`;
CREATE TABLE `url_categories_denied` (
  `ucdid` int(10) unsigned NOT NULL auto_increment,
  `gid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ucdid`),
  KEY `FK_url_categories_denied_url_categories` (`cid`),
  CONSTRAINT `FK_url_categories_denied_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_denied`
--

/*!40000 ALTER TABLE `url_categories_denied` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_categories_denied` ENABLE KEYS */;


--
-- Definition of table `url_categories_denied_log`
--

DROP TABLE IF EXISTS `url_categories_denied_log`;
CREATE TABLE `url_categories_denied_log` (
  `ucdlid` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `unique_id` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(125) NOT NULL,
  PRIMARY KEY  (`ucdlid`),
  KEY `FK_url_categories_denied_log_categories` (`cid`),
  CONSTRAINT `FK_url_categories_denied_log_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_denied_log`
--

/*!40000 ALTER TABLE `url_categories_denied_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_categories_denied_log` ENABLE KEYS */;


--
-- Definition of table `url_categories_filter`
--

DROP TABLE IF EXISTS `url_categories_filter`;
CREATE TABLE `url_categories_filter` (
  `ucfid` int(10) unsigned NOT NULL auto_increment,
  `keys` varchar(255) NOT NULL default '',
  `cid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ucfid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_filter`
--

/*!40000 ALTER TABLE `url_categories_filter` DISABLE KEYS */;
INSERT INTO `url_categories_filter` (`ucfid`,`keys`,`cid`) VALUES 
 (1,'download',1),
 (2,'xxx',11),
 (3,'porn',11),
 (4,'soft',7),
 (5,'sport',12),
 (6,'search',5),
 (7,'find',5),
 (8,'mail',4),
 (9,'educa',6);
/*!40000 ALTER TABLE `url_categories_filter` ENABLE KEYS */;


--
-- Definition of table `url_categories_keywords`
--

DROP TABLE IF EXISTS `url_categories_keywords`;
CREATE TABLE `url_categories_keywords` (
  `uckid` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `keyword` varchar(40) character set utf8 NOT NULL,
  PRIMARY KEY  (`uckid`),
  UNIQUE KEY `Index_keyword` (`keyword`),
  KEY `FK_url_categories_keywords_url_categories` (`cid`),
  CONSTRAINT `FK_url_categories_keywords_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_keywords`
--

/*!40000 ALTER TABLE `url_categories_keywords` DISABLE KEYS */;
INSERT INTO `url_categories_keywords` (`uckid`,`cid`,`keyword`) VALUES 
 (41,63,'icq'),
 (88,4,'ÐœÐ°Ð³Ð°Ð·Ð¸Ð½'),
 (89,4,'ÑÑƒÐ¿ÐµÑ€Ð¼Ð°Ñ€ÐºÐµÑ‚'),
 (90,4,'Ð¿Ð¾ÐºÑƒÐ¿ÐºÐ¸'),
 (91,4,'ÑÐºÐ¸Ð´ÐºÐ¸'),
 (92,41,'ÑÐ¸Ð³Ð°Ñ€ÐµÑ‚Ñ‹'),
 (93,41,'Ð½Ð¸ÐºÐ¾Ñ‚Ð¸Ð½'),
 (94,41,'ÐºÑƒÑ€ÐµÐ½Ð¸Ðµ'),
 (95,20,'Ð´ÐµÑ‚Ð¸'),
 (96,20,'Ð¸Ð³Ñ€ÑƒÑˆÐºÐ¸'),
 (97,45,'Ð¿ÑƒÑ‚ÐµÑˆÐµÑÑ‚Ð²Ð¸Ñ'),
 (98,45,'Ñ‚ÑƒÑ€'),
 (99,16,'crack'),
 (100,16,'serial'),
 (101,56,'army'),
 (102,28,'mail'),
 (103,28,'Ð¿Ð¾Ñ‡Ñ‚Ð°'),
 (104,28,'Ð¿Ð¾Ñ‡Ñ‚Ð¾Ð²Ñ‹Ð¹'),
 (105,28,'Ð¿Ð¾Ñ‡Ñ‚Ð¾Ð²Ñ‹Ðµ'),
 (106,28,'Ð¿Ð¸ÑÑŒÐ¼Ð¾'),
 (107,28,'message'),
 (108,28,'email'),
 (109,28,'e-mail'),
 (110,21,'ÑÐµÐ·Ð¾Ð½'),
 (111,21,'ÑÐµÑ€Ð¸Ñ'),
 (112,21,'Ð²Ð¸Ð´ÐµÐ¾'),
 (113,21,'ÑÐµÑ€Ð¸Ð°Ð»'),
 (114,21,'Ñ‚ÐµÐ»ÐµÐ²Ð¸Ð´ÐµÐ½Ð¸Ðµ'),
 (115,19,'warcraft'),
 (116,19,'Ð¸Ð³Ñ€Ñ‹'),
 (117,19,'Ð¸Ð³Ñ€Ð°Ñ‚ÑŒ'),
 (118,19,'blizzard'),
 (119,39,'Ð´Ð¸Ð½Ð°Ñ‚ÑƒÑ€Ð°Ñ‚'),
 (120,39,'Ð½Ð°Ñ€ÐºÐ¾Ñ‚Ð¸ÐºÐ¸'),
 (121,36,'security'),
 (122,36,'Ð±ÐµÐ·Ð¾Ð¿Ð°ÑÐ½Ð¾ÑÑ‚ÑŒ'),
 (123,36,'firewall'),
 (124,36,'antivirus'),
 (125,36,'Ð°Ð½Ñ‚Ð¸Ð²Ð¸Ñ€ÑƒÑ'),
 (126,36,'Ð±Ñ€ÑÐ½Ð´Ð¼Ð°ÑƒÑÑ€'),
 (127,36,'Ñ„Ð°Ð¹Ñ€Ð²Ð¾Ð»'),
 (128,36,'Ñ„Ð°ÐµÑ€Ð²Ð¾Ð»'),
 (129,51,'Ñ€Ð°Ð±Ð¾Ñ‚Ð°'),
 (130,51,'job'),
 (131,51,'Ð²Ð°ÐºÐ°Ð½ÑÐ¸Ð¸'),
 (132,51,'Ñ€ÐµÐ·ÑŽÐ¼Ðµ'),
 (133,51,'resume'),
 (134,51,'curriculum'),
 (135,51,'vitae'),
 (136,51,'ÑÐ¾Ð±ÐµÑÐµÐ´Ð¾Ð²Ð°Ð½Ð¸Ðµ'),
 (137,51,'Ñ€Ð°Ð±Ð¾Ñ‚Ñƒ'),
 (138,51,'Ñ€Ð°Ð±Ð¾Ñ‚Ñ‹'),
 (139,51,'Ñ€Ð°Ð±Ð¾Ñ‚Ðµ'),
 (140,31,'mobile'),
 (141,31,'phone'),
 (142,31,'Ð¼Ð¾Ð±Ð¸Ð»ÑŒÐ½Ñ‹Ðµ'),
 (143,31,'Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ñ‹'),
 (144,31,'Ð¼Ð¾Ð±Ð¸Ð»ÑŒÐ½Ð¸Ðº'),
 (145,31,'iphone'),
 (146,31,'nokia'),
 (147,31,'motorolla'),
 (148,31,'ericsson'),
 (149,24,'Ð¼ÑƒÐ·Ñ‹ÐºÐ°'),
 (150,24,'Ñ€Ð¾Ðº-Ð½-Ñ€Ð¾Ð»Ð»'),
 (151,24,'Ð¿Ð¾Ð¿'),
 (152,24,'guitar'),
 (153,24,'Ð³Ð¸Ñ‚Ð°Ñ€Ð°'),
 (154,24,'Ð³Ð¸Ñ‚Ð°Ñ€Ð½Ñ‹Ð¹'),
 (155,24,'Ð¼ÑƒÐ·Ñ‹ÐºÐ°Ð»ÑŒÐ½Ñ‹Ðµ'),
 (156,49,'Ð¿Ñ€Ð¸Ñ€Ð¾Ð´Ð°'),
 (157,49,'ÑÐ¾Ð±Ð°ÐºÐ¸'),
 (158,49,'ÐºÐ¾ÑˆÐºÐ¸'),
 (159,49,'Ð¶Ð¸Ð²Ð¾Ñ‚Ð½Ñ‹Ðµ'),
 (165,50,'personal'),
 (166,50,'Ð¿ÐµÑ€ÑÐ¾Ð½Ð°Ð»ÑŒÐ½Ñ‹Ð¹'),
 (167,50,'Ð»Ð¸Ñ‡Ð½Ñ‹Ð¹'),
 (168,50,'Ð»Ð¸Ñ‡Ð½Ð°Ñ'),
 (169,33,'Ð¿Ð¾Ð¸ÑÐº'),
 (170,33,'search'),
 (171,33,'Ð½Ð°Ð¹Ñ‚Ð¸'),
 (172,33,'Ð¸ÑÐºÐ°Ñ‚ÑŒ'),
 (173,33,'Ð¿Ð¾Ð¸ÑÐºÐ¾Ð²Ð°Ñ'),
 (174,33,'Ð¿Ð¾Ð¸ÑÐºÐ¾Ð²Ñ‹Ð¹'),
 (175,33,'yandex'),
 (176,33,'google'),
 (177,33,'Ð¸Ñ‰Ñƒ'),
 (178,33,'Ð½Ð°Ð¹Ð´Ñ‘Ñ‚ÑÑ'),
 (179,33,'Ð½Ð°Ð¹Ð´ÐµÑ‚ÑÑ'),
 (180,33,'ÑÐ½Ð´ÐµÐºÑ'),
 (181,30,'Ð½Ð¾Ð²Ð¾ÑÑ‚Ð¸'),
 (182,30,'blog'),
 (183,30,'news'),
 (184,30,'livejournal'),
 (185,30,'Ð¶Ð¶'),
 (186,30,'blogs'),
 (187,30,'Ð±Ð»Ð¾Ð³'),
 (188,30,'Ð´Ð½ÐµÐ²Ð½Ð¸Ðº'),
 (189,30,'Ð´Ð½ÐµÐ²Ð½Ð¸ÐºÐ¸'),
 (214,34,'ajax'),
 (215,34,'classname'),
 (216,34,'prototype'),
 (217,34,'callback'),
 (218,34,'buffer'),
 (219,34,'code'),
 (220,34,'programming'),
 (221,34,'ÑÐ¾Ñ„Ñ‚'),
 (222,34,'Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ'),
 (223,34,'Ð¿Ñ€Ð¾Ð³Ð¸'),
 (224,34,'Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ñ‹'),
 (225,34,'software'),
 (226,34,'ÐºÐ¾Ð¼Ð¿ÑŒÑŽÑ‚ÐµÑ€Ñ‹'),
 (227,34,'hardware'),
 (228,34,'3d'),
 (229,34,'Ñ†Ð¸Ñ„Ñ€Ð¾Ð²Ð¾ÐµÐ²Ð¸Ð´ÐµÐ¾'),
 (230,34,'cpu'),
 (231,34,'ram'),
 (232,34,'Ð¿Ñ€Ð¸Ð½Ñ‚ÐµÑ€Ñ‹'),
 (233,34,'typeof'),
 (234,34,'class'),
 (235,34,'variable'),
 (236,34,'param'),
 (237,34,'applyif'),
 (238,34,'ÐºÐ¾Ð´'),
 (239,34,'ÑÐ¾Ñ€Ñ†Ñ‹'),
 (240,34,'sourcecode'),
 (241,34,'Ð¸ÑÑ…Ð¾Ð´Ð½Ð¸ÐºÐ¸'),
 (242,0,'ÑÐ°Ð½ÐºÑ‚'),
 (243,0,'Ð¿ÐµÑ‚ÐµÑ€Ð±ÑƒÑ€Ð³'),
 (244,0,'Ð¿Ð¾Ð³Ð¾Ð´Ð°'),
 (245,0,'Ð·ÐµÐ½Ð¸Ñ‚'),
 (246,0,'Ð¼Ð°Ñ€ÐºÐµÑ‚'),
 (247,0,'Ð¿Ð°Ñ€Ð¾Ð»ÑŒ'),
 (248,0,'Ð¿ÐµÑ‚ÐµÑ€Ð±ÑƒÑ€Ð³Ð°'),
 (249,0,'Ñ…Ñ€Ð¾Ð½Ð¸ÐºÐ¸'),
 (250,0,'Ð½Ð°Ñ€Ð½Ð¸Ð¸'),
 (251,0,'Ð¿Ñ€Ð¸Ð½Ñ†'),
 (252,0,'Ð¿ÐµÑ€Ð²Ñ‹Ð¹'),
 (253,0,'minus');
/*!40000 ALTER TABLE `url_categories_keywords` ENABLE KEYS */;


--
-- Definition of table `url_categories_match`
--

DROP TABLE IF EXISTS `url_categories_match`;
CREATE TABLE `url_categories_match` (
  `u2cid` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`u2cid`),
  UNIQUE KEY `Index_url_unique` (`url`),
  KEY `url_index` (`url`),
  KEY `FK_url2category_url_categories` (`cid`),
  CONSTRAINT `FK_url2category_url_categories` FOREIGN KEY (`cid`) REFERENCES `url_categories` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `url_categories_match`
--

/*!40000 ALTER TABLE `url_categories_match` DISABLE KEYS */;
INSERT INTO `url_categories_match` (`u2cid`,`url`,`cid`) VALUES 
 (1,'narod.yandex.ru',33),
 (2,'127.0.0.1',0),
 (3,'toolbarqueries.google.ru',33),
 (4,'market.yandex.ru',4),
 (6,'www.google.com',33),
 (7,'mail.google.com',28),
 (8,'www.google.ru',33),
 (9,'mobbit.info',31),
 (10,'autocontext.begun.ru',0),
 (11,'an.yandex.ru',33),
 (12,'mt1.google.com',33),
 (14,'kh3.google.ru',33),
 (15,'kh0.google.ru',33),
 (16,'kh2.google.ru',33),
 (17,'mt2.google.com',33),
 (18,'mt3.google.com',33),
 (19,'kh1.google.ru',33),
 (20,'sb.google.com',33),
 (21,'vkontakte.ru',43),
 (22,'cadbis.googlecode.com',33),
 (23,'code.google.com',34),
 (24,'www.gmodules.com',33),
 (25,'tortoisesvn.tigris.org',34),
 (26,'2ip.ru',0),
 (27,'pagead2.googlesyndication.com',33),
 (28,'download.rbn.com',34),
 (29,'vkadre.ru',0),
 (30,'lostfilm.ru',21),
 (31,'sj.91.com',34),
 (32,'sj1.91.com',34),
 (33,'www.ru-iphone.com',0),
 (34,'www.iphones.ru',0),
 (35,'video-stats.video.google.com',0),
 (36,'rs346.rapidshare.com',35),
 (37,'cache.googlevideo.com',21),
 (38,'www.ziphone.org',0),
 (39,'download.ziphone.org',0),
 (40,'www.youtube.com',21),
 (41,'ru.ziphone.org',0),
 (42,'www.decoder.com.ua',31),
 (43,'smartzone.ru',24),
 (44,'itunes.com',24),
 (45,'crl2.entrust.net',34),
 (46,'maps.google.ru',33),
 (47,'news.google.ru',33),
 (48,'www.vesti.ru',27),
 (49,'reklama.mb.vesti.ru',0),
 (50,'nnn.novoteka.ru',0),
 (52,'c.bb.ru',34),
 (53,'voanews.com',27),
 (54,'www.strana.ru',27),
 (55,'www4.tizer.adv.vz.ru',0),
 (56,'vgtrk.mb.vesti.ru',0),
 (57,'www.smi.ru',27),
 (58,'468.smi.ru',0),
 (59,'rb.rfn.ru',0),
 (60,'del.icio.us',30),
 (61,'css.experts-exchange.com',0),
 (62,'www.hotelrestaurantbegonia.be',0),
 (63,'images.google.ru',33),
 (64,'www.bacnet.ru',0),
 (65,'prokovalya.narod.ru',0),
 (66,'soft.mydiv.net',0),
 (67,'www.regimenmunicipal.go.cr',0),
 (68,'www.softkey.pl',34),
 (69,'zhzh.info',8),
 (70,'smecsia.blogspot.com',30),
 (71,'mail.ru',27),
 (72,'counter.rambler.ru',0),
 (73,'win.mail.ru',27),
 (74,'r.mail.ru',28),
 (75,'1link.ru',0),
 (76,'3k.mail.ru',27),
 (78,'prototype-window.xilinus.com',0),
 (79,'http://blogger.com',30);
/*!40000 ALTER TABLE `url_categories_match` ENABLE KEYS */;


--
-- Definition of table `url_categories_unsensewords`
--

DROP TABLE IF EXISTS `url_categories_unsensewords`;
CREATE TABLE `url_categories_unsensewords` (
  `ucuwid` int(10) unsigned NOT NULL auto_increment,
  `keyword` varchar(20) character set utf8 NOT NULL,
  PRIMARY KEY  (`ucuwid`),
  UNIQUE KEY `Index_keyword_unique` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_unsensewords`
--

/*!40000 ALTER TABLE `url_categories_unsensewords` DISABLE KEYS */;
INSERT INTO `url_categories_unsensewords` (`ucuwid`,`keyword`) VALUES 
 (1,'+'),
 (2,','),
 (3,'.'),
 (4,'/'),
 (5,':'),
 (6,'='),
 (7,'Â©'),
 (8,'Â·'),
 (9,'â€”'),
 (10,'Ñ'),
 (11,'ÑÐ²ÐµÑ€Ñ…Ñƒ'),
 (12,'ÑÐ»ÑƒÑ‡Ð°Ð¹'),
 (20,'ÑÐ»ÑƒÑ‡Ð°Ðµ'),
 (21,'ÑÐ½Ð°Ñ‡Ð°Ð»Ð°'),
 (22,'ÑÐ½Ð¸Ð·Ñƒ'),
 (16,'ÑÐ¾Ð±Ð¾Ð¹'),
 (23,'ÑÐµÐ¹Ñ‡Ð°Ñ'),
 (24,'ÑÑ‚Ñƒ'),
 (25,'ÑÑ‚Ð°'),
 (26,'ÑÑ‚Ð¸'),
 (27,'ÑÑ‚Ð¸Ñ…'),
 (28,'ÑÑ‚Ð¸Ð¼'),
 (29,'ÑÑ‚Ð¾'),
 (30,'ÑÑ‚Ð¾Ñ‚'),
 (31,'ÑÑ‚Ð¾Ð³Ð¾'),
 (32,'ÑÑ‚Ð¾Ð¹'),
 (33,'ÑÑ‚Ð¾Ð¼Ñƒ'),
 (34,'Ñ'),
 (35,'ÑƒÐ¶'),
 (36,'ÑƒÐ¶Ðµ'),
 (37,'Ñ‚Ñ‹'),
 (38,'Ñ‚Ð°Ðº'),
 (39,'Ñ‚Ð°ÐºÐ¶Ðµ'),
 (40,'Ñ‚Ð°Ð¼'),
 (41,'Ñ‚Ð²Ð¾Ð¹'),
 (42,'Ñ‚Ð¾'),
 (43,'Ñ‚ÐµÐ±Ñ'),
 (44,'Ñ‚ÐµÐ±Ðµ'),
 (45,'Ñ‡Ñ‚Ð¾Ð±Ñ‹'),
 (46,'Ñ‡Ð°ÑÑ‚Ð¾'),
 (47,'Ñ‡ÐµÑ€ÐµÐ·'),
 (48,'Ñ‡ÐµÐ³Ð¾'),
 (49,'Ð°'),
 (50,'Ð±ÑƒÐ´Ñƒ'),
 (51,'Ð±ÑƒÐ´ÑƒÑ‚'),
 (52,'Ð±ÑƒÐ´ÐµÑˆÑŒ'),
 (53,'Ð±ÑƒÐ´ÐµÑ‚'),
 (54,'Ð±ÑƒÐ´ÐµÑ‚Ðµ'),
 (55,'Ð±ÑƒÐ´ÐµÐ¼'),
 (56,'Ð±Ñ‹'),
 (57,'Ð±Ñ‹ÑÑ‚Ñ€Ð¾'),
 (58,'Ð±Ñ‹Ñ‚ÑŒ'),
 (59,'Ð±Ñ‹Ð»Ð¾'),
 (60,'Ð±Ð¾Ð»ÐµÐµ'),
 (61,'Ð±ÐµÐ·'),
 (62,'Ð²'),
 (63,'Ð²ÑÑ‘'),
 (64,'Ð²ÑÐµ'),
 (65,'Ð²ÑÐµÐ³Ð´Ð°'),
 (66,'Ð²ÑÐµÐ³Ð¾'),
 (67,'Ð²Ñ‹'),
 (68,'Ð²Ñ‹ÑˆÐµ'),
 (69,'Ð²Ð°Ñ'),
 (70,'Ð²Ð°ÑˆÐµ'),
 (71,'Ð²Ð°Ð¼'),
 (72,'Ð²Ð¼ÐµÑÑ‚Ð¾'),
 (73,'Ð²Ð¾Ñ‚'),
 (74,'Ð²Ð¾Ð¾Ð±Ñ‰Ðµ'),
 (75,'Ð³Ð´Ðµ'),
 (76,'Ð´Ð»Ñ'),
 (77,'Ð´Ð¾Ð¿ÑƒÑÑ‚Ð¸Ð¼'),
 (78,'Ð´Ð¾Ð¿ÑƒÑÑ‚Ð¸Ð¼Ð¾'),
 (79,'Ð¶Ðµ'),
 (80,'Ð·Ð°'),
 (81,'Ð·Ð°Ñ‡ÐµÐ¼'),
 (82,'Ð·Ð´ÐµÑÑŒ'),
 (83,'Ð¸'),
 (84,'Ð¸Ñ…'),
 (85,'Ð¸Ð·'),
 (86,'Ð¸Ð»Ð¸'),
 (87,'Ð¸Ð¼'),
 (88,'Ð¸Ð½Ð¾Ð³Ð´Ð°'),
 (89,'ÐºÑ‚Ð¾'),
 (90,'ÐºÑ€Ð¾Ð¼Ðµ'),
 (91,'ÐºÐ°Ðº'),
 (92,'ÐºÐ°ÐºÐ°Ñ'),
 (93,'ÐºÐ°ÐºÐ¸Ð¼'),
 (94,'ÐºÐ°ÐºÐ¸Ðµ'),
 (95,'ÐºÐ°ÐºÐ¾Ð¹'),
 (96,'ÐºÐ°ÐºÐ¾Ð¼Ñƒ'),
 (97,'ÐºÐ¾Ð³Ð´Ð°'),
 (98,'ÐºÐ¾Ð³Ð¾'),
 (99,'ÐºÐ¾Ð¼Ñƒ'),
 (100,'ÐºÐ¾Ð½Ñ†Ñƒ'),
 (101,'ÐºÐ¾Ð½Ñ†Ð°'),
 (102,'ÐºÐ¾Ð½Ñ†Ðµ'),
 (103,'ÐºÐ¾Ð½ÐµÑ†'),
 (104,'Ð»Ð¸'),
 (19,'Ð»Ð¸ÑˆÑŒ'),
 (105,'Ð»Ð¸Ð±Ð¾'),
 (106,'Ð¼Ñ‹'),
 (107,'Ð¼Ð½Ðµ'),
 (108,'Ð¼Ð¾Ñ‘'),
 (109,'Ð¼Ð¾Ð³'),
 (110,'Ð¼Ð¾Ð³Ñƒ'),
 (111,'Ð¼Ð¾Ð³Ð»Ð°'),
 (112,'Ð¼Ð¾Ð³Ð»Ð¸'),
 (113,'Ð¼Ð¾Ð¶Ð½Ð¾'),
 (114,'Ð¼Ð¾Ð¶ÐµÑˆÑŒ'),
 (115,'Ð¼Ð¾Ð¶ÐµÑ‚Ðµ'),
 (116,'Ð¼Ð¾Ð¶ÐµÐ¼'),
 (117,'Ð¼Ð¾Ð¸'),
 (118,'Ð¼ÐµÑÑ‚Ð¾'),
 (119,'Ð¼ÐµÐ½Ñ'),
 (120,'Ð¼ÐµÐ½ÐµÐµ'),
 (121,'Ð½Ñƒ'),
 (122,'Ð½Ñ‘Ð¼'),
 (123,'Ð½Ð°'),
 (124,'Ð½Ð°Ñ'),
 (125,'Ð½Ð°ÑˆÐµ'),
 (126,'Ð½Ð°Ñ‡Ð°Ð»Ñƒ'),
 (127,'Ð½Ð°Ñ‡Ð°Ð»Ð°'),
 (128,'Ð½Ð°Ñ‡Ð°Ð»Ð¾'),
 (129,'Ð½Ð°Ñ‡Ð°Ð»Ðµ'),
 (130,'Ð½Ð°Ð´'),
 (131,'Ð½Ð°Ð¼'),
 (132,'Ð½Ð°Ð¼Ð¸'),
 (133,'Ð½Ð¸'),
 (134,'Ð½Ð¸Ð±ÑƒÐ´ÑŒ'),
 (135,'Ð½Ð¾'),
 (136,'Ð½Ðµ'),
 (15,'Ð½ÐµÐ³Ð¾'),
 (137,'Ð½ÐµÐ»ÑŒÐ·Ñ'),
 (18,'Ð½ÐµÐ¾Ð±Ñ…Ð¾Ð´Ð¸Ð¼Ð¾'),
 (138,'Ð¾'),
 (139,'Ð¾Ñ‚'),
 (140,'Ð¾Ð±'),
 (141,'Ð¾Ð±Ñ‰ÐµÐµ'),
 (142,'Ð¾Ð±Ñ‹Ñ‡Ð½Ð¾'),
 (143,'Ð¾Ð½'),
 (144,'Ð¾Ð½Ð°'),
 (145,'Ð¾Ð½Ð¸'),
 (146,'Ð¾Ð½Ð¾'),
 (147,'Ð¿Ñ€Ð¸'),
 (17,'Ð¿Ñ€Ð¸ÑˆÐ»Ð¾ÑÑŒ'),
 (14,'Ð¿Ñ€Ð¾ÑÑ‚Ð¾'),
 (148,'Ð¿Ð¾'),
 (149,'Ð¿Ð¾ÑÑ‚Ð¾ÑÐ½Ð½Ð¾'),
 (13,'Ð¿Ð¾Ñ‚Ð¾Ð¼'),
 (150,'Ð¿Ð¾Ñ‡Ñ‚Ð¸'),
 (151,'Ð¿Ð¾Ð´'),
 (152,'Ð¿Ð¾ÐºÐ°'),
 (153,'Ð¿ÐµÑ€ÐµÐ´'),
 (154,'ÐµÑÑ‚ÑŒ'),
 (155,'ÐµÑÐ»Ð¸'),
 (156,'ÐµÑ‰Ðµ'),
 (157,'ÐµÐ³Ð¾'),
 (158,'ÐµÐ¹'),
 (159,'ÐµÐ¼Ñƒ');
/*!40000 ALTER TABLE `url_categories_unsensewords` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
