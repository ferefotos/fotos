-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1:3306
-- Létrehozás ideje: 2019. Feb 11. 18:36
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
('1548421063718.jpg', 'fercsivox', 16, 'Hajómalom', 'Ráckeve', 'f/2.2', '1/160', 50, 5, 'Huawei EVA-L19', '', '2018-10-07', 'landscape', 1),
('1549747044811.jpg', 'fercsivox', 17, 'Fagyos virág', '', 'f/2.2', '1/160', 50, 5, 'Huawei EVA-L19', '', '2018-02-18', 'landscape', 1),
('1549747115596.jpg', 'fercsivox', 3, 'Téli etető', '', 'f/4', '1/1000', 800, 280, 'Canon EOS 70D', 'EF200mm f/2.8L USM', '2016-01-25', 'landscape', 1),
('1549747217266.jpg', 'fercsivox', 3, 'Lovak', '', 'f/8', '1/200', 100, 40, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2011-06-25', 'landscape', 1),
('1549747299792.jpg', 'fercsivox', 10, 'Lovasi', '', 'f/3.5', '1/80', NULL, 55, 'Canon PowerShot S2 IS', '', '2006-07-06', 'landscape', 1),
('1549747485106.jpg', 'fercsivox', 10, 'DM Zágráb', '', 'f/4', '1/160', 1600, 96, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2013-07-18', 'landscape', 1),
('1549747812750.jpg', 'fercsivox', 16, 'Bálák', '', 'f/11', '1/125', 100, 24, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2011-06-25', 'landscape', 1),
('1549747865280.jpg', 'fercsivox', 16, 'Spartacus ösvény', '', 'f/9', '1/250', 200, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-05-21', 'landscape', 1),
('1549747886255.jpg', 'fercsivox', 16, '', '', 'f/9', '1/200', 200, 12, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-05-21', 'landscape', 1),
('1549747947139.jpg', 'fercsivox', 18, 'Ostia', '', 'f/9', '1/250', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-07-14', 'landscape', 1),
('1549748017956.jpg', 'fercsivox', 18, 'Ostia', '', 'f/9', '1/250', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-07-14', 'landscape', 1),
('1549748137936.jpg', 'fercsivox', 18, 'Milano, Navigli', '', 'f/7.1', '1/320', 100, 18, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2018-09-20', 'landscape', 1),
('1549748206855.jpg', 'fercsivox', 18, 'Róma', '', 'f/6.3', '1/50', 200, 73, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2013-07-19', 'portrait', 1),
('1549748270437.jpg', 'fercsivox', 18, 'Appia Antica', 'Róma', 'f/8', '1/320', 200, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-01', 'landscape', 1),
('1549748587573.jpg', 'fercsivox', 18, 'Basilica di Santa Croce', 'Róma', 'f/7.1', '1/200', 400, 28, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-25', 'landscape', 1),
('1549748613840.jpg', 'fercsivox', 18, 'Róma, Trastevere', '', 'f/6.3', '1/250', 320, 60, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-06-07', 'landscape', 1),
('1549748782847.jpg', 'tesztelek', 17, 'Varenna', 'A Garda-tó partján', 'f/7.1', '1/160', 200, 67, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2013-07-18', 'landscape', 1),
('1549748913566.jpg', 'tesztelek', 18, 'Nógrádi vár', '', 'f/11', '1/160', 100, 15, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2012-07-28', 'landscape', 1),
('1549748984787.jpg', 'tesztelek', 16, 'Kilátás', 'Nógrádi vár', 'f/11', '1/125', 100, 11, 'Canon EOS 600D', 'EF-S10-22mm f/3.5-4.5 USM', '2012-07-28', 'landscape', 1),
('1549749049757.jpg', 'tesztelek', 17, 'Árvalányhaj', '', 'f/5', '1/125', 200, 73, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2011-06-04', 'landscape', 1),
('1549749091849.jpg', 'tesztelek', 17, 'virág', '', 'f/4', '1/1000', 200, 60, 'Canon EOS 70D', 'EF-S60mm f/2.8 Macro USM', '2017-05-21', 'portrait', 1),
('1549749227716.jpg', 'tesztelek', 16, 'Száraz faág a hegyoldalban', 'Spartacus-ösvény', 'f/9', '1/250', 200, 22, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-05-21', 'landscape', 1),
('1549749453393.jpg', 'tesztelek', 16, 'Nagy-Sas-hegy', 'Börzsöny', 'f/7.1', '1/160', 200, 28, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-22', 'landscape', 1),
('1549749507643.jpg', 'tesztelek', 16, 'Őszi patakvölgy', 'Börzsöny', 'f/11', '1/8', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-11-04', 'landscape', 1),
('1549749714346.jpg', 'tesztelek', 18, 'Róma látkép', '', 'f/7.1', '1/500', 100, 82, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-03', 'landscape', 1),
('1549749814285.jpg', 'tesztelek', 18, 'Colosseum', 'Róma', 'f/10', '1/200', 100, 10, 'Canon EOS 600D', 'EF-S10-22mm f/3.5-4.5 USM', '2012-10-06', 'landscape', 1),
('1549749883310.jpg', 'tesztelek', 18, 'Forum Romanum', 'Róma', 'f/8', '1/200', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-07-15', 'landscape', 1),
('1549750054531.jpg', 'tesztelek', 18, 'Károly-híd', 'Prága', 'f/10', '1/640', 200, 22, 'Canon EOS 600D', 'EF-S10-22mm f/3.5-4.5 USM', '2012-09-15', 'landscape', 1),
('1549750237876.jpg', 'tesztelek', 18, 'Verona', '', 'f/7.1', '1/250', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-13', 'landscape', 1),
('1549753135718.jpg', 'fercsivox', 6, 'Verona Aréna', 'Kék órában', 'f/5', '1', 100, 12, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2016-07-09', 'landscape', 1),
('1549753179611.jpg', 'fercsivox', 18, 'Perugia', '', 'f/8', '1/80', 500, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-28', 'portrait', 1),
('1549753300165.jpg', 'fercsivox', 6, 'Szent Péter Bazilika', '', 'f/4', '4/10', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-24', 'landscape', 1),
('1549753439673.jpg', 'fercsivox', 16, 'Gráciák bérce', 'Vértes', 'f/9', '1/160', 200, 12, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-04-14', 'landscape', 1),
('1549753492751.jpg', 'fercsivox', 17, 'Tulipánok', '', 'f/5.6', '1/320', 100, 200, 'Canon EOS 70D', 'EF200mm f/2.8L USM', '2017-04-04', 'landscape', 1),
('1549753522672.jpg', 'fercsivox', 17, 'Gomba', '', 'f/2.8', '1/1600', 100, 200, 'Canon EOS 70D', 'EF200mm f/2.8L USM', '2016-10-28', 'portrait', 1),
('1549753626598.jpg', 'fercsivox', 3, 'Cinegék', '', 'f/2.2', '1/50', 65, 5, 'Huawei EVA-L19', '', '2018-05-18', 'wide', 1),
('1549753698592.jpg', 'fercsivox', 16, 'Őszi fények', 'Börzsöny', 'f/8', '1/50', 200, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'landscape', 1),
('1549753765546.jpg', 'fercsivox', 16, 'Elmosott sínek', 'A 90-es években a Csarna-patak áradása elmosta a kisvasút sínjeit.', 'f/11', '1/40', 100, 35, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'landscape', 1),
('1549753805342.jpg', 'fercsivox', 16, 'Patak', '', 'f/4', '1/80', 200, 84, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2013-10-26', 'landscape', 1),
('1549753970845.jpg', 'fercsivox', 4, 'Virágos Veronában', '', 'f/5.6', '1/80', 200, 73, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-09', 'landscape', 1),
('1549754137145.jpg', 'fercsivox', 5, 'Villám', '', 'f/4', '10', 100, 24, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2011-05-21', 'landscape', 1),
('1549754258128.jpg', 'fercsivox', 16, 'Szigetcsúcs', 'Tass, Csepel-sziget déli szigetcsúcs', 'f/7.1', '1/250', 100, 67, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-07', 'landscape', 1),
('1549789330452.jpg', 'fercsivox', 18, 'Városkép Orvietoból', 'Orvieto, Olaszország', 'f/8', '1/250', 100, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1549789437348.jpg', 'fercsivox', 18, 'Alkonyat hangulat', 'Velence', 'f/8', '1/640', 200, 47, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-29', 'landscape', 1),
('1549789579438.jpg', 'fercsivox', 17, 'Leánykökörcsin', '', 'f/5.6', '1/100', 100, 147, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-04-15', 'landscape', 1),
('1549789671569.jpg', 'fercsivox', 16, 'Olasz táj', 'Kilátás Bagnoregioból', 'f/7.1', '1/500', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1549789776928.jpg', 'fercsivox', 18, 'Kolostor', 'Basilica dei Santi Quattro Coronati', 'f/8', '1/320', 800, 13, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2018-06-25', 'portrait', 1),
('1549789876566.jpg', 'fercsivox', 6, 'Verona éjszaka', 'Ponte di Castelvecchio', 'f/5.6', '4', 100, 55, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-12', 'landscape', 1),
('1549789995845.jpg', 'fercsivox', 18, 'Vár Sirmioneban', '', '', '', NULL, NULL, '', '', '0000-00-00', 'landscape', 1),
('1549790324891.jpg', 'tesztelek', 11, 'Tavasz Brisighellában', 'Brisighella, Olaszország', 'f/9', '1/30', 100, 24, 'Canon EOS 450D', '', '2011-03-13', 'landscape', 1),
('1549790386919.jpg', 'tesztelek', 4, 'Piac', 'Barcelona', 'f/8', '1/60', 400, 40, 'Canon EOS 600D', 'EF24-105mm f/4L IS USM', '2014-01-15', 'landscape', 1),
('1549790700726.jpg', 'tesztelek', 18, '', 'Ravenna', 'f/6.3', '1/400', 200, 35, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-30', 'landscape', 1),
('1549790835450.jpg', 'tesztelek', 18, '', 'Verona', 'f/5.6', '1/800', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2015-09-15', 'landscape', 1),
('1549790926877.jpg', 'tesztelek', 18, '', 'Padova', 'f/9', '1/80', 800, 40, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-28', 'landscape', 1),
('1549790985835.jpg', 'tesztelek', 18, '', 'London', 'f/5', '1/50', 250, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2015-02-11', 'portrait', 1),
('1549791115122.jpg', 'tesztelek', 16, '', 'Börzsöny', 'f/4', '1/250', 100, 28, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-10-15', 'landscape', 1),
('1549791215454.jpg', 'fercsivox', 18, '', 'Róma, Trastevere', 'f/5', '1/100', 400, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-06-07', 'landscape', 1),
('1549791282253.jpg', 'fercsivox', 18, '', 'Róma, Appia Antica', 'f/8', '1/800', 200, 95, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2016-07-01', 'landscape', 1),
('1549904760367.jpg', 'fercsivox', 10, 'David Gilmour koncerten', 'Verona Arena', 'f/4', '1/320', 1600, 67, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2015-09-14', 'landscape', 1),
('1549904863369.jpg', 'fercsivox', 10, 'Billy Idol', 'Rock in Roma', 'f/4', '1/250', 8000, 105, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-06-09', 'portrait', 1),
('1549904994857.jpg', 'fercsivox', 4, 'Kisautók', 'Valahol Róma utcáin', 'f/4.5', '1/160', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-25', 'landscape', 1),
('1549905053121.jpg', 'fercsivox', 6, 'Angyalvár éjszaka', 'Róma', 'f/4', '8/10', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-24', 'landscape', 1),
('1549905249380.jpg', 'fercsivox', 18, 'Velence', '', 'f/8', '1/640', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-06-29', 'portrait', 1),
('1549905351728.jpg', 'fercsivox', 17, 'Őszi levelek', '', 'f/2.8', '1/400', 200, 60, 'Canon EOS 70D', 'EF-S60mm f/2.8 Macro USM', '2018-11-04', 'landscape', 1),
('1549906312669.jpg', 'tesztelek', 16, 'Alkonyat a Dunán', 'Ráckeve', 'f/2.2', '1/160', 50, 5, 'Huawei EVA-L19', '', '2018-10-07', 'landscape', 1),
('1549906375631.jpg', 'tesztelek', 16, 'Kisvasút a Csarna-patak völgyébe', '', 'f/8', '1/30', 100, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-10-14', 'portrait', 1),
('1549906457340.jpg', 'tesztelek', 18, 'Orvieto', '', 'f/7.1', '1/250', 100, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-06-27', 'landscape', 1),
('1549906533769.jpg', 'fercsivox', 18, 'Ciprusok a magasból', 'Orvieto, Olaszország', 'f/5.6', '1/200', 200, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-06-26', 'landscape', 1),
('1549906615848.jpg', 'fercsivox', 18, 'Bagnoregio', 'Bagnoregio, Olaszország', 'f/9', '1/320', 100, 58, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-06-27', 'landscape', 1),
('1549906691958.jpg', 'fercsivox', 18, 'Palermo', '', 'f/6.3', '1/640', 200, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2015-07-14', 'landscape', 1),
('1549906812263.jpg', 'fercsivox', 16, 'Ősz a horgásztónál', 'Sződliget', 'f/7.1', '1/100', 100, 24, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-10-16', 'landscape', 1),
('1549906875668.jpg', 'fercsivox', 16, 'Horgászbódék', 'Sződliget', 'f/6.3', '1/60', 100, 55, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2017-10-16', 'landscape', 1),
('1549906908975.jpg', 'fercsivox', 16, 'Szakadékban', 'Holdvilág-árok', 'f/9', '1/40', 100, 10, 'Canon EOS 70D', 'EF-S10-22mm f/3.5-4.5 USM', '2017-11-05', 'landscape', 1),
('1549906960416.jpg', 'fercsivox', 16, 'Ragyogás', '', 'f/10', '1/60', 200, 92, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2018-11-04', 'portrait', 1),
('1549907082309.jpg', 'fercsivox', 18, '', 'Róma', 'f/7.1', '1/500', 200, 55, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-06-09', 'portrait', 1),
('1549907219662.jpg', 'fercsivox', 18, '', 'London', 'f/10', '1/200', 200, 32, 'Canon EOS 70D', 'EF24-105mm f/4L IS USM', '2014-03-30', 'landscape', 1);

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
(5, 'Égbolt'),
(6, 'Éjszakai'),
(7, 'Életképek'),
(8, 'Elkapott pillanat'),
(9, 'Hangulatképek'),
(10, 'Koncert'),
(11, 'Madártávlat'),
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
-- Tábla szerkezet ehhez a táblához `kedvelesek`
--

DROP TABLE IF EXISTS `kedvelesek`;
CREATE TABLE IF NOT EXISTS `kedvelesek` (
  `kedvelo` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `foto` varchar(25) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`kedvelo`,`foto`),
  KEY `ertekelo` (`kedvelo`),
  KEY `foto` (`foto`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `kedvelesek`
--

INSERT INTO `kedvelesek` (`kedvelo`, `foto`) VALUES
('fercsivox', '1549789876566.jpg'),
('fercsivox', '1549790324891.jpg'),
('fercsivox', '1549790386919.jpg'),
('fercsivox', '1549790926877.jpg'),
('fercsivox', '1549791215454.jpg'),
('fercsivox', '1549904760367.jpg'),
('fercsivox', '1549904994857.jpg'),
('fercsivox', '1549906312669.jpg'),
('fercsivox', '1549906457340.jpg'),
('fercsivox', '1549906875668.jpg'),
('fercsivox', '1549906908975.jpg'),
('fercsivox', '1549907082309.jpg'),
('tesztelek', '1548421063718.jpg'),
('tesztelek', '1549789776928.jpg'),
('tesztelek', '1549789876566.jpg'),
('tesztelek', '1549790324891.jpg'),
('tesztelek', '1549790386919.jpg'),
('tesztelek', '1549790700726.jpg'),
('tesztelek', '1549790835450.jpg'),
('tesztelek', '1549791115122.jpg'),
('tesztelek', '1549791215454.jpg'),
('tesztelek', '1549904994857.jpg'),
('tesztelek', '1549905053121.jpg'),
('tesztelek', '1549905249380.jpg'),
('tesztelek', '1549906375631.jpg'),
('tesztelek', '1549906533769.jpg'),
('tesztelek', '1549906615848.jpg'),
('tesztelek', '1549906691958.jpg'),
('tesztelek', '1549906875668.jpg'),
('tesztelek', '1549906908975.jpg'),
('tesztelek', '1549906960416.jpg'),
('tesztelek', '1549907082309.jpg'),
('tesztelek', '1549907219662.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kedvencek`
--

DROP TABLE IF EXISTS `kedvencek`;
CREATE TABLE IF NOT EXISTS `kedvencek` (
  `jelolo` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `filename` varchar(25) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`jelolo`,`filename`),
  KEY `jelolo` (`jelolo`),
  KEY `filename` (`filename`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `kedvencek`
--

INSERT INTO `kedvencek` (`jelolo`, `filename`) VALUES
('fercsivox', '1548421063718.jpg'),
('fercsivox', '1549789876566.jpg'),
('fercsivox', '1549790386919.jpg'),
('fercsivox', '1549790700726.jpg'),
('fercsivox', '1549790835450.jpg'),
('fercsivox', '1549791115122.jpg'),
('fercsivox', '1549791282253.jpg'),
('fercsivox', '1549904994857.jpg'),
('fercsivox', '1549905249380.jpg'),
('fercsivox', '1549906312669.jpg'),
('fercsivox', '1549906375631.jpg'),
('fercsivox', '1549906533769.jpg'),
('fercsivox', '1549906615848.jpg'),
('fercsivox', '1549906691958.jpg'),
('fercsivox', '1549906875668.jpg'),
('fercsivox', '1549906908975.jpg'),
('tesztelek', '1549789876566.jpg'),
('tesztelek', '1549789995845.jpg'),
('tesztelek', '1549790324891.jpg'),
('tesztelek', '1549791115122.jpg'),
('tesztelek', '1549904760367.jpg'),
('tesztelek', '1549904994857.jpg'),
('tesztelek', '1549906375631.jpg'),
('tesztelek', '1549906533769.jpg'),
('tesztelek', '1549906875668.jpg'),
('tesztelek', '1549906960416.jpg'),
('tesztelek', '1549907082309.jpg');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kommentek`
--

DROP TABLE IF EXISTS `kommentek`;
CREATE TABLE IF NOT EXISTS `kommentek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ertekelo` varchar(16) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `kep` varchar(32) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `komment` text COLLATE utf8mb4_hungarian_ci NOT NULL,
  `datum` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ertekelo` (`ertekelo`),
  KEY `kep` (`kep`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

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
-- Megkötések a táblához `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`artist`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foto_ibfk_2` FOREIGN KEY (`katid`) REFERENCES `kategoria` (`id`) ON UPDATE CASCADE;

--
-- Megkötések a táblához `kedvelesek`
--
ALTER TABLE `kedvelesek`
  ADD CONSTRAINT `kedvelesek_ibfk_1` FOREIGN KEY (`foto`) REFERENCES `foto` (`file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kedvelesek_ibfk_2` FOREIGN KEY (`kedvelo`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `kedvencek`
--
ALTER TABLE `kedvencek`
  ADD CONSTRAINT `kedvencek_ibfk_1` FOREIGN KEY (`filename`) REFERENCES `foto` (`file`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kedvencek_ibfk_2` FOREIGN KEY (`jelolo`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `kommentek`
--
ALTER TABLE `kommentek`
  ADD CONSTRAINT `kommentek_ibfk_1` FOREIGN KEY (`ertekelo`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kommentek_ibfk_2` FOREIGN KEY (`kep`) REFERENCES `foto` (`file`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
