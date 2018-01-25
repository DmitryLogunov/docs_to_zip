CREATE DATABASE `tenders`;

USE `tenders`;

-- Дамп структуры для таблица tenders.lots
CREATE TABLE IF NOT EXISTS `lots` (
  `lotID` bigint(20) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` bigint(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `price` double NOT NULL,
  `tradeState` enum('prepare','trade','checkWinner','confirm') NOT NULL,
  `nonPriced` tinyint(2) DEFAULT NULL,
  `dateStart` date NOT NULL,
  `dateEnd` date NOT NULL,
  `notify` tinyint(4) DEFAULT NULL,
  `notifyDate` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `deleteDate` datetime DEFAULT NULL,
  `type` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`lotID`),
  KEY `userID` (`userID`),
  KEY `deleted` (`deleted`),
  KEY `notify` (`notify`),
  KEY `start` (`tradeState`)
) ENGINE=InnoDB AUTO_INCREMENT=537 DEFAULT CHARSET=utf8;

-- Дамп структуры для таблица tenders.offersAttachments
CREATE TABLE IF NOT EXISTS `offersAttachments` (
  `fileID` varchar(36) NOT NULL,
  `tenderOffersID` bigint(20) DEFAULT NULL,
  `fileType` varchar(4) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `uploaded` datetime DEFAULT NULL,
  `originalName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`fileID`),
  KEY `tenderOfferID` (`tenderOffersID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп структуры для таблица tenders.registry
CREATE TABLE IF NOT EXISTS `registry` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(75) NOT NULL,
  `created` datetime NOT NULL DEFAULT '2009-07-23 00:01:00',
  `approved` datetime DEFAULT NULL,
  `companyType` varchar(10) NOT NULL,
  `opfID` bigint(20) DEFAULT NULL,
  `companyName` tinytext NOT NULL,
  `companyAddress` tinytext NOT NULL,
  `companyPostalAddress` tinytext NOT NULL,
  `ownerType` varchar(15) NOT NULL,
  `phone1` tinytext NOT NULL,
  `phone2` tinytext,
  `fax` tinytext,
  `homepage` tinytext,
  `person` tinytext NOT NULL,
  `authorizedPerson` tinytext NOT NULL,
  `authorizedPersonStatus` tinytext NOT NULL,
  `authorizationReason` tinytext NOT NULL,
  `payment` varchar(35) DEFAULT NULL,
  `nds` tinyint(2) DEFAULT NULL,
  `selfPark` int(11) DEFAULT NULL,
  `attractPark` int(11) DEFAULT NULL,
  `autopark` text,
  `moreInfo` text,
  `blocked` tinyint(1) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `dispatcherAuthStatusID` bigint(20) DEFAULT NULL,
  `totalInsurance` tinyint(1) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `ban` datetime DEFAULT NULL,
  `dispatcherUID` varchar(35) DEFAULT NULL,
  `contractID` varchar(150) DEFAULT NULL,
  `INN` varchar(12) DEFAULT NULL,
  `KPP` varchar(9) DEFAULT NULL,
  `op_account` varchar(20) DEFAULT NULL,
  `cor_account` varchar(20) DEFAULT NULL,
  `bankName` tinytext,
  `BIK` varchar(9) DEFAULT NULL,
  `OGRN` varchar(15) NOT NULL,
  `OGRNdat` date DEFAULT NULL,
  `OKVED` varchar(50) NOT NULL,
  `warrantyNum` varchar(20) DEFAULT NULL,
  `warrantyDat` date DEFAULT NULL,
  `SMScellphone` varchar(11) DEFAULT NULL,
  `subscribeEmail` varchar(50) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `citiesInsurance` text,
  `reqWork` tinyint(1) DEFAULT NULL,
  `sleep` date DEFAULT NULL,
  `managerID` int(11) DEFAULT NULL,
  `addressID` bigint(20) DEFAULT NULL,
  `postalAddressID` bigint(20) DEFAULT NULL,
  `sendedReason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `managerID` (`managerID`),
  KEY `reqWork` (`reqWork`),
  KEY `addressID` (`addressID`),
  KEY `postalAddressID` (`postalAddressID`),
  KEY `opfID` (`opfID`)
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8;


-- Дамп структуры для таблица tenders.tenderOffers
CREATE TABLE IF NOT EXISTS `tenderOffers` (
  `offerID` bigint(20) NOT NULL AUTO_INCREMENT,
  `offerDate` datetime NOT NULL,
  `registryID` bigint(20) NOT NULL,
  `lotID` bigint(20) NOT NULL,
  `offerPrice` bigint(20) NOT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `approveDate` datetime DEFAULT NULL,
  `approvedByUserID` bigint(20) NOT NULL,
  `confirmed` tinyint(1) DEFAULT NULL,
  `confirmDate` datetime DEFAULT NULL,
  PRIMARY KEY (`offerID`),
  UNIQUE KEY `userID` (`registryID`,`lotID`)
) ENGINE=InnoDB AUTO_INCREMENT=1654 DEFAULT CHARSET=utf8;


