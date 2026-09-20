-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2026 at 03:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `ISBN` varchar(15) NOT NULL,
  `BookTitle` varchar(40) NOT NULL,
  `Author` varchar(20) NOT NULL,
  `Edition` int(11) NOT NULL,
  `Year` int(5) NOT NULL,
  `Category` bigint(3) NOT NULL,
  `Reserved` varchar(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`ISBN`, `BookTitle`, `Author`, `Edition`, `Year`, `Category`, `Reserved`) VALUES
('093-403992', 'Computers in Busines', 'Alicia Oneill', 3, 1997, 3, 'N'),
('23472-8729', 'Exploring Peru', 'Stephanie Birchi', 4, 2005, 5, 'N'),
('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, 2, 'N'),
('23u8-923849', 'A guide to nutrition', 'John Thorpe', 2, 1997, 1, 'N'),
('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, 7, 'N'),
('82n8-308', 'computers for idiots', 'Susan ONeill', 5, 1998, 4, 'N'),
('9823-23984', 'My life in picture', 'Kevin Graham', 8, 2004, 1, 'N'),
('9823-2403-0', 'Da Vinci Code', 'Dan Brown', 1, 2003, 8, 'N'),
('9823-98345', 'How to cook Italian', 'Jamie Oliver', 2, 2005, 7, 'N'),
('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, 2, 'N'),
('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, 3, 'Y'),
('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, 8, 'N'),
('993-004-00', 'My life in bits', 'John Smith', 1, 2001, 1, 'N'),
('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, 1, 'N');

-- Add 286 deterministic sample books, bringing the catalogue to 300 books total.
-- Categories 1-8 are used; category 9 is intentionally not used.
INSERT INTO `books` (`ISBN`, `BookTitle`, `Author`, `Edition`, `Year`, `Category`, `Reserved`)
SELECT
  CONCAT('GEN-', LPAD(n, 6, '0')),
  CONCAT('Sample Library Book ', n),
  CONCAT('Author ', n),
  ((n - 1) MOD 5) + 1,
  1980 + ((n - 1) MOD 46),
  ((n - 1) MOD 8) + 1,
  'N'
FROM (
  SELECT ones.n + tens.n * 10 + hundreds.n * 100 + 1 AS n
  FROM
    (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS ones
  CROSS JOIN
    (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS tens
  CROSS JOIN
    (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2) AS hundreds
  WHERE ones.n + tens.n * 10 + hundreds.n * 100 < 286
) AS generated_books;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` bigint(3) NOT NULL,
  `CategoryDescription` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryDescription`) VALUES
(1, 'Health'),
(2, 'Business'),
(3, 'Biography'),
(4, 'Technology'),
(5, 'Travel'),
(6, 'Self-Help'),
(7, 'Cookery'),
(8, 'Fiction');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `ISBN` varchar(15) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `ReservedDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ISBN`, `Username`, `ReservedDate`) VALUES
('98234-029384', 'joseph.crotty', '2008-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `AddressLine1` varchar(50) NOT NULL DEFAULT 'none',
  `AddressLine2` varchar(50) NOT NULL,
  `City` varchar(20) NOT NULL DEFAULT 'none',
  `County` varchar(20) NOT NULL DEFAULT 'none',
  `Telephone` varchar(10) NOT NULL DEFAULT 'none',
  `Mobile` varchar(10) NOT NULL DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

-- These are fictional seed credentials and contact numbers for development only.
INSERT INTO `users` (`Username`, `Password`, `FirstName`, `Surname`, `AddressLine1`, `AddressLine2`, `City`, `County`, `Telephone`, `Mobile`) VALUES
('alan.mckenna', 'Cedar!Alan84', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', 'Dublin', '012348761', '0873487612'),
('joseph.crotty', 'Harbour!Joe72', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', 'Dublin', '012347892', '0873478921'),
('tom.behan', 'Valley!Tom63', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', 'Dublin', '012346583', '0873465830'),
('amelia.byrne', 'River!Amelia26', 'Amelia', 'Byrne', '1 River Road', 'Rathmines', 'Dublin', 'Dublin', '012345184', '0853451842'),
('ben.murphy', 'Oak!BenMurphy47', 'Ben', 'Murphy', '2 Oak Avenue', 'Blackrock', 'Dublin', 'Dublin', '012345295', '0853452953'),
('cara.walsh', 'Main!CaraWalsh58', 'Cara', 'Walsh', '3 Main Street', 'Swords', 'Dublin', 'Dublin', '012345306', '0853453064'),
('daniel.ryan', 'Church!Daniel39', 'Daniel', 'Ryan', '4 Church Road', 'Bray', 'Wicklow', 'Wicklow', '012762417', '0867624175'),
('ella.doyle', 'Harbour!Ella64', 'Ella', 'Doyle', '5 Harbour View', 'Galway City', 'Galway', 'Galway', '091562728', '0865627286'),
('finn.kelly', 'College!Finn85', 'Finn', 'Kelly', '6 College Road', 'Cork City', 'Cork', 'Cork', '021487639', '0874876397'),
('grace.obrien', 'Park!Grace91', 'Grace', 'OBrien', '7 Park Lane', 'Limerick City', 'Limerick', 'Limerick', '061348540', '0853485408'),
('harry.nolan', 'Station!Harry73', 'Harry', 'Nolan', '8 Station Road', 'Waterford City', 'Waterford', 'Waterford', '051823651', '0868236519'),
('isla.kavanagh', 'Market!Isla46', 'Isla', 'Kavanagh', '9 Market Square', 'Kilkenny Town', 'Kilkenny', 'Kilkenny', '056781462', '0877814620'),
('jack.foley', 'Green!JackFoley52', 'Jack', 'Foley', '10 Green Road', 'Navan', 'Meath', 'Meath', '046902573', '0859025731'),
('katie.quinn', 'Castle!Katie68', 'Katie', 'Quinn', '11 Castle Street', 'Naas', 'Kildare', 'Kildare', '045874684', '0868746842'),
('liam.dunne', 'Hill!LiamDunne37', 'Liam', 'Dunne', '12 Hill Road', 'Drogheda', 'Louth', 'Louth', '041983795', '0879837953'),
('mia.hughes', 'Lake!MiaHughes49', 'Mia', 'Hughes', '13 Lakeside', 'Athlone', 'Westmeath', 'Westmeath', '090642806', '0856428064'),
('noah.murray', 'Forest!Noah61', 'Noah', 'Murray', '14 Forest Road', 'Letterkenny', 'Donegal', 'Donegal', '074912917', '0869129175'),
('orla.power', 'Mill!OrlaPower74', 'Orla', 'Power', '15 Mill Road', 'Carlow Town', 'Carlow', 'Carlow', '059914028', '0879140286'),
('paul.kennedy', 'Valley!Paul83', 'Paul', 'Kennedy', '16 Valley Road', 'Ennis', 'Clare', 'Clare', '065682139', '0856821397'),
('ruby.mahon', 'Seaview!Ruby56', 'Ruby', 'Mahon', '17 Seaview', 'Tralee', 'Kerry', 'Kerry', '066712240', '0867122408'),
('sam.healy', 'Church!SamHealy88', 'Sam', 'Healy', '18 Church Lane', 'Sligo Town', 'Sligo', 'Sligo', '071913351', '0879133519'),
('saoirse.whelan', 'Orchard!Saoirse42', 'Saoirse', 'Whelan', '19 Orchard Road', 'Wexford Town', 'Wexford', 'Wexford', '053914462', '0859144620'),
('theo.clarke', 'Garden!TheoClarke65', 'Theo', 'Clarke', '20 Garden Road', 'Mullingar', 'Westmeath', 'Westmeath', '044934573', '0869345731');

--
-- Indexes for dumped tables
--

ALTER TABLE `books`
  ADD PRIMARY KEY (`ISBN`),
  ADD KEY `Category` (`Category`);

ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

ALTER TABLE `reservations`
  ADD KEY `Username` (`Username`),
  ADD KEY `reservations` (`ISBN`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`Username`);

--
-- Constraints for dumped tables
--

ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`Category`) REFERENCES `category` (`CategoryID`);

ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`ISBN`) REFERENCES `books` (`ISBN`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`Username`) REFERENCES `users` (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
