-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.24-community-nt


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories`
--

/*!40000 ALTER TABLE `url_categories` DISABLE KEYS */;
INSERT INTO `url_categories` (`cid`,`title`) VALUES 
 (0,'ÐšÐ°Ñ‚ÐµÐ³Ð¾Ñ€Ð¸Ñ Ð½ÐµÐ¾Ð¿Ñ€ÐµÐ´ÐµÐ»ÐµÐ½Ð°'),
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
 (31,'Mobile Telephony');
INSERT INTO `url_categories` (`cid`,`title`) VALUES 
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `url_categories_keywords`
--

/*!40000 ALTER TABLE `url_categories_keywords` DISABLE KEYS */;
INSERT INTO `url_categories_keywords` (`uckid`,`cid`,`keyword`) VALUES 
 (16,34,'ajax'),
 (17,34,'buffer'),
 (18,34,'code'),
 (19,34,'programming'),
 (20,34,'Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ'),
 (21,34,'Ð¿Ñ€Ð¾Ð³Ð¸'),
 (22,34,'Ð¿Ñ€Ð¾Ð³Ñ€Ð°Ð¼Ð¼Ñ‹'),
 (23,34,'software'),
 (24,34,'ÐºÐ¾Ð¼Ð¿ÑŒÑŽÑ‚ÐµÑ€Ñ‹'),
 (25,34,'hardware'),
 (26,34,'3d'),
 (27,34,'Ñ†Ð¸Ñ„Ñ€Ð¾Ð²Ð¾ÐµÐ²Ð¸Ð´ÐµÐ¾'),
 (28,34,'cpu'),
 (29,34,'ram'),
 (30,34,'Ð¿Ñ€Ð¸Ð½Ñ‚ÐµÑ€Ñ‹'),
 (31,50,''),
 (32,30,'Ð±Ð»Ð¾Ð³'),
 (33,30,'blog'),
 (34,30,'news'),
 (35,30,'Ð½Ð¾Ð²Ð¾ÑÑ‚Ð¸'),
 (36,31,'mobile'),
 (37,31,'phone'),
 (38,31,'Ð¼Ð¾Ð±Ð¸Ð»ÑŒÐ½Ñ‹Ðµ'),
 (39,31,'Ñ‚ÐµÐ»ÐµÑ„Ð¾Ð½Ñ‹'),
 (40,31,'Ð¼Ð¾Ð±Ð¸Ð»ÑŒÐ½Ð¸Ðº'),
 (41,63,'icq'),
 (45,33,'Ð¿Ð¾Ð¸ÑÐº'),
 (46,33,'search'),
 (47,33,'Ð½Ð°Ð¹Ñ‚Ð¸'),
 (48,33,'Ð¸Ñ‰Ñƒ'),
 (49,33,'Ð¸ÑÐºÐ°Ñ‚ÑŒ');
INSERT INTO `url_categories_keywords` (`uckid`,`cid`,`keyword`) VALUES 
 (50,33,'Ð½Ð°Ð¹Ð´Ñ‘Ñ‚ÑÑ');
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

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
 (37,'cache.googlevideo.com',21);
INSERT INTO `url_categories_match` (`u2cid`,`url`,`cid`) VALUES 
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
 (69,'zhzh.info',0),
 (70,'smecsia.blogspot.com',0),
 (71,'mail.ru',27),
 (72,'counter.rambler.ru',0),
 (73,'win.mail.ru',27);
INSERT INTO `url_categories_match` (`u2cid`,`url`,`cid`) VALUES 
 (74,'r.mail.ru',28),
 (75,'1link.ru',0),
 (76,'3k.mail.ru',0),
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
 (12,'ÑÐ½Ð¸Ð·Ñƒ'),
 (13,'ÑÐµÐ¹Ñ‡Ð°Ñ'),
 (14,'ÑÑ‚Ñƒ'),
 (15,'ÑÑ‚Ð°'),
 (16,'ÑÑ‚Ð¸'),
 (17,'ÑÑ‚Ð¸Ñ…'),
 (18,'ÑÑ‚Ð¸Ð¼'),
 (19,'ÑÑ‚Ð¾'),
 (20,'ÑÑ‚Ð¾Ñ‚'),
 (21,'ÑÑ‚Ð¾Ð³Ð¾'),
 (22,'ÑÑ‚Ð¾Ð¹'),
 (23,'ÑÑ‚Ð¾Ð¼Ñƒ'),
 (24,'Ñ'),
 (25,'ÑƒÐ¶'),
 (26,'ÑƒÐ¶Ðµ'),
 (27,'Ñ‚Ñ‹'),
 (28,'Ñ‚Ð°Ðº'),
 (29,'Ñ‚Ð°Ð¼'),
 (30,'Ñ‚Ð²Ð¾Ð¹'),
 (31,'Ñ‚Ð¾'),
 (32,'Ñ‚ÐµÐ±Ñ'),
 (33,'Ñ‚ÐµÐ±Ðµ'),
 (34,'Ñ‡Ñ‚Ð¾Ð±Ñ‹'),
 (103,'Ñ‡ÐµÑ€ÐµÐ·'),
 (35,'Ñ‡ÐµÐ³Ð¾'),
 (36,'\\'),
 (37,'Ð°'),
 (38,'Ð±ÑƒÐ´Ñƒ'),
 (39,'Ð±ÑƒÐ´ÑƒÑ‚'),
 (40,'Ð±ÑƒÐ´ÐµÑˆÑŒ'),
 (41,'Ð±ÑƒÐ´ÐµÑ‚'),
 (42,'Ð±ÑƒÐ´ÐµÑ‚Ðµ');
INSERT INTO `url_categories_unsensewords` (`ucuwid`,`keyword`) VALUES 
 (43,'Ð±ÑƒÐ´ÐµÐ¼'),
 (44,'Ð±Ñ‹'),
 (45,'Ð±Ñ‹ÑÑ‚Ñ€Ð¾'),
 (46,'Ð±Ñ‹Ñ‚ÑŒ'),
 (47,'Ð±Ñ‹Ð»Ð¾'),
 (48,'Ð±Ð¾Ð»ÐµÐµ'),
 (49,'Ð±ÐµÐ·'),
 (50,'Ð²'),
 (51,'Ð²ÑÑ‘'),
 (52,'Ð²Ñ‹'),
 (53,'Ð²Ð°Ñ'),
 (54,'Ð²Ð°ÑˆÐµ'),
 (55,'Ð²Ð°Ð¼'),
 (56,'Ð²Ð¼ÐµÑÑ‚Ð¾'),
 (57,'Ð²Ð¾Ñ‚'),
 (58,'Ð²Ð¾Ð¾Ð±Ñ‰Ðµ'),
 (59,'Ð³Ð´Ðµ'),
 (60,'Ð´Ð»Ñ'),
 (61,'Ð´Ð¾Ð¿ÑƒÑÑ‚Ð¸Ð¼'),
 (62,'Ð´Ð¾Ð¿ÑƒÑÑ‚Ð¸Ð¼Ð¾'),
 (63,'Ð¶Ðµ'),
 (64,'Ð·Ð°'),
 (65,'Ð·Ð°Ñ‡ÐµÐ¼'),
 (66,'Ð¸'),
 (67,'Ð¸Ñ…'),
 (68,'Ð¸Ð·'),
 (69,'Ð¸Ð¼'),
 (70,'ÐºÑ‚Ð¾'),
 (71,'ÐºÐ°Ðº'),
 (72,'ÐºÐ°ÐºÐ°Ñ'),
 (73,'ÐºÐ°ÐºÐ¸Ð¼'),
 (74,'ÐºÐ°ÐºÐ¸Ðµ'),
 (75,'ÐºÐ°ÐºÐ¾Ð¹'),
 (76,'ÐºÐ°ÐºÐ¾Ð¼Ñƒ'),
 (77,'ÐºÐ¾Ð³Ð´Ð°'),
 (78,'ÐºÐ¾Ð³Ð¾'),
 (79,'ÐºÐ¾Ð¼Ñƒ');
INSERT INTO `url_categories_unsensewords` (`ucuwid`,`keyword`) VALUES 
 (80,'Ð»Ð¸'),
 (81,'Ð»Ð¸Ð±Ð¾'),
 (82,'Ð¼Ñ‹'),
 (83,'Ð¼Ð½Ðµ'),
 (84,'Ð¼Ð¾Ñ‘'),
 (85,'Ð¼Ð¾Ð³'),
 (86,'Ð¼Ð¾Ð³Ñƒ'),
 (87,'Ð¼Ð¾Ð³Ð»Ð°'),
 (88,'Ð¼Ð¾Ð³Ð»Ð¸'),
 (89,'Ð¼Ð¾Ð¶ÐµÑˆÑŒ'),
 (90,'Ð¼Ð¾Ð¶ÐµÑ‚Ðµ'),
 (91,'Ð¼Ð¾Ð¶ÐµÐ¼'),
 (92,'Ð¼Ð¾Ð¸'),
 (93,'Ð¼ÐµÑÑ‚Ð¾'),
 (94,'Ð¼ÐµÐ½Ñ'),
 (95,'Ð¼ÐµÐ½ÐµÐµ'),
 (96,'Ð½Ñƒ'),
 (97,'Ð½Ñ‘Ð¼'),
 (98,'Ð½Ð°'),
 (99,'Ð½Ð°Ñ'),
 (100,'Ð½Ð°ÑˆÐµ'),
 (101,'Ð½Ð°Ð´'),
 (102,'Ð½Ð°Ð¼'),
 (104,'Ð½Ð°Ð¼Ð¸'),
 (105,'Ð½Ð¸'),
 (106,'Ð½Ð¸Ð±ÑƒÐ´ÑŒ'),
 (107,'Ð½Ð¾'),
 (108,'Ð½Ðµ'),
 (109,'Ð¾'),
 (110,'Ð¾Ñ‚'),
 (111,'Ð¾Ð±Ñ‰ÐµÐµ'),
 (112,'Ð¾Ð½'),
 (113,'Ð¾Ð½Ð°'),
 (114,'Ð¾Ð½Ð¸'),
 (115,'Ð¾Ð½Ð¾'),
 (116,'Ð¿Ð¾'),
 (117,'Ð¿Ð¾Ð´'),
 (118,'Ð¿ÐµÑ€ÐµÐ´'),
 (119,'ÐµÑÑ‚ÑŒ'),
 (120,'ÐµÑÐ»Ð¸');
INSERT INTO `url_categories_unsensewords` (`ucuwid`,`keyword`) VALUES 
 (121,'ÐµÐ³Ð¾'),
 (122,'ÐµÐ¹'),
 (123,'ÐµÐ¼Ñƒ');
/*!40000 ALTER TABLE `url_categories_unsensewords` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
