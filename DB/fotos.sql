-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1:3306
-- Létrehozás ideje: 2019. Jan 29. 08:41
-- Kiszolgáló verziója: 5.7.23
-- PHP verzió: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `fotos`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ertekel`
--

DROP TABLE IF EXISTS `ertekel`;
CREATE TABLE IF NOT EXISTS `ertekel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ertekelo` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `foto` varchar(25) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `kedvel` tinyint(1) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb4_hungarian_ci,
  `datum` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ertekelo` (`ertekelo`),
  KEY `foto` (`foto`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foto`
--

DROP TABLE IF EXISTS `foto`;
CREATE TABLE IF NOT EXISTS `foto` (
  `file` varchar(32) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `artist` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `katid` tinyint(2) UNSIGNED NOT NULL,
  `cim` varchar(32) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `story` varchar(500) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `blende` varchar(10) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `zarido` varchar(10) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `iso` int(10) DEFAULT NULL,
  `focus` smallint(5) DEFAULT NULL,
  `kamera` varchar(64) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `obi` varchar(64) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `class` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  PRIMARY KEY (`file`),
  KEY `artist` (`artist`),
  KEY `katid` (`katid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `foto`
--

INSERT INTO `foto` (`file`, `artist`, `katid`, `cim`, `story`, `blende`, `zarido`, `iso`, `focus`, `kamera`, `obi`, `date`, `class`, `public`) VALUES
('1548333212287.jpg', 'fercsivox', 16, 'Őszi fények', 'Készült a Börzsönyben', 'f/8', '1/50', 200, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'landscape', 1),
('1548333482650.jpg', 'fercsivox', 16, 'Sínpár az erdőben', 'A Kemencei Erdei Múzeumvasút nyomvonalán a Börzsönyben.', 'f/8', '1/30', 100, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'portrait', 1),
('1548333587177.jpg', 'fercsivox', 16, 'Elmosott sínek', 'A 90-es években a Csarna-patak áradása elmosta a kisvasút sínjeit.', 'f/11', '1/40', 100, 35, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'landscape', 1),
('1548334022750.jpg', 'fercsivox', 18, 'Bagnoregio', 'Bagnoregio, Olaszország', 'f/9', '1/320', 100, 58, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1548334125127.jpg', 'fercsivox', 18, 'Út a várba', 'Bagnoregio, Olaszország', 'f/7.1', '1/400', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1548334168690.jpg', 'fercsivox', 16, 'Olasz táj', 'Kilátás Bagnoregioból', 'f/7.1', '1/500', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1548334468544.jpg', 'fercsivox', 16, 'Ciprusok a magasból', 'Orvieto, Olaszország', 'f/5.6', '1/200', 200, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-06-26', 'landscape', 1),
('1548334531187.jpg', 'fercsivox', 18, 'Városkép Orvietoból', 'Orvieto, Olaszország', 'f/8', '1/250', 100, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1548405446218.jpg', 'fercsivox', 18, 'Verona éjszaka', 'Ponte di Castelvecchio', 'f/5.6', '4', 100, 55, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-12', 'landscape', 1),
('1548405890311.jpg', 'fercsivox', 18, 'Kékóra', 'Verona Arena', 'f/5', '1', 100, 12, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2016-07-09', 'landscape', 1),
('1548418110865.jpg', 'fercsivox', 10, 'David Gilmour koncerten', 'Verona Aréna', 'f/4', '1/320', 1600, 67, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2015-09-14', 'landscape', 1),
('1548418333646.jpg', 'fercsivox', 10, 'Billy Idol', 'Rock in Roma', 'f/4', '1/250', 8000, 105, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-06-09', 'portrait', 1),
('1548418834520.jpg', 'fercsivox', 18, 'Kisautók', 'Valahol Róma utcáin', 'f/4.5', '1/160', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-25', 'landscape', 1),
('1548419375627.jpg', 'fercsivox', 18, 'Kolostor', 'Basilica dei Santi Quattro Coronati', 'f/8', '1/320', 800, 13, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2018-06-25', 'portrait', 1),
('1548419928746.jpg', 'fercsivox', 18, 'Alkonyat hangulat', 'Velence', 'f/8', '1/640', 200, 47, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-29', 'landscape', 1),
('1548420077958.jpg', 'fercsivox', 18, 'Velence', '', 'f/8', '1/640', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-29', 'portrait', 1),
('1548420387328.jpg', 'fercsivox', 16, 'Szakadékban', 'Holdvilág-árok', 'f/9', '1/40', 100, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-11-05', 'landscape', 1),
('1548420558511.jpg', 'fercsivox', 16, 'Sződligeti horgásztó', '', 'f/6.3', '1/60', 100, 55, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-10-16', 'landscape', 1),
('1548420657737.jpg', 'fercsivox', 16, 'Horgászó', 'Sződliget', 'f/8', '1/100', 100, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-10-16', 'landscape', 1),
('1548420743921.jpg', 'fercsivox', 16, 'Ragyogás', 'Börzsöny', 'f/10', '1/60', 200, 92, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-11-04', 'portrait', 1),
('1548420793601.jpg', 'fercsivox', 17, 'Ellenfény', '', 'f/2.8', '1/400', 200, 60, 'Canon EOS 70D', 'EF-S60mm f/2.8 Macro USM', '2018-11-04', 'landscape', 1),
('1548421063718.jpg', 'fercsivox', 16, 'Hajómalom', 'Ráckeve', 'f/2.2', '1/160', 50, 5, 'Huawei EVA-L19', '', '2018-10-07', 'landscape', 1),
('1548421175630.jpg', 'fercsivox', 16, 'Szigetcsúcs', 'Tass, Csepel-sziget déli szigetcsúcs', 'f/7.1', '1/250', 100, 67, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-07', 'landscape', 1),
('1548497897924.jpg', 'tesztelek', 17, 'Hegyoldal', '', 'f/9', '1/250', 200, 22, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-05-21', 'landscape', 1),
('1548497931903.jpg', 'tesztelek', 17, 'kanyargó út', '', 'f/8', '1/100', 200, 60, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-05-21', 'landscape', 0),
('1548497957499.jpg', 'tesztelek', 17, 'virág', '', 'f/4', '1/1000', 200, 60, 'Canon EOS 70D', 'EF-S60mm f/2.8 Macro USM', '2017-05-21', 'portrait', 0),
('1548497996478.jpg', 'tesztelek', 16, 'Fent a hegyen', '', 'f/9', '1/250', 200, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-05-21', 'landscape', 1),
('1548514047898.jpg', 'fercsivox', 18, 'Colosseum', '', 'f/8', '1/100', 400, 40, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-25', 'portrait', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kategoria`
--

DROP TABLE IF EXISTS `kategoria`;
CREATE TABLE IF NOT EXISTS `kategoria` (
  `id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kategoria` varchar(64) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `kategoria`
--

INSERT INTO `kategoria` (`id`, `kategoria`) VALUES
(1, 'Absztrakt'),
(2, 'Akt'),
(3, 'Állatfotó'),
(4, 'Csendélet'),
(5, 'Divat'),
(6, 'Égbolt'),
(7, 'Életképek'),
(8, 'Elkapott pillanat'),
(9, 'Hangulatképek'),
(10, 'Koncert'),
(11, 'Légifotók'),
(12, 'Makró'),
(13, 'Portré'),
(14, 'Sport'),
(15, 'Szociofotók'),
(16, 'Tájkép'),
(17, 'Természet'),
(18, 'Város, építészet'),
(19, 'egyéb');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kedvenc`
--

DROP TABLE IF EXISTS `kedvenc`;
CREATE TABLE IF NOT EXISTS `kedvenc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jelolo` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `filename` varchar(25) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jelolo` (`jelolo`),
  KEY `filename` (`filename`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userid` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jelszo` char(40) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `nev` varchar(64) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `pkep` varchar(20) COLLATE utf8mb4_hungarian_ci DEFAULT 'avatar.png',
  `rolam` text COLLATE utf8mb4_hungarian_ci,
  `cam` varchar(64) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `lens` varchar(64) COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `user`
--

INSERT INTO `user` (`userid`, `jelszo`, `email`, `nev`, `pkep`, `rolam`, `cam`, `lens`) VALUES
('fercsivox', '10175103924d05533cd5f1057fa95874ec5ae0b3', 'reg@ferefoto.hu', 'Szatmári Ferenc', 'fercsivox.jpg', 'Magamról pár sor', 'Canon EOS 70D', 'Canon 24-105 F4 L'),
('tesztelek', '10175103924d05533cd5f1057fa95874ec5ae0b3', 'info@ferefoto.hu', 'Teszt Elek', 'tesztelek.jpg', '', 'Canon EOS 800D', 'Canon 24-105 F4 L');

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `ertekel`
--
ALTER TABLE `ertekel`
  ADD CONSTRAINT `ertekel_ibfk_1` FOREIGN KEY (`foto`) REFERENCES `foto` (`file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ertekel_ibfk_2` FOREIGN KEY (`ertekelo`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`artist`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foto_ibfk_2` FOREIGN KEY (`katid`) REFERENCES `kategoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `kedvenc`
--
ALTER TABLE `kedvenc`
  ADD CONSTRAINT `kedvenc_ibfk_1` FOREIGN KEY (`filename`) REFERENCES `foto` (`file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kedvenc_ibfk_2` FOREIGN KEY (`jelolo`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
