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
('98234-029384', 'joecrotty', '2008-10-11');

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

INSERT INTO `users` (`Username`, `Password`, `FirstName`, `Surname`, `AddressLine1`, `AddressLine2`, `City`, `County`, `Telephone`, `Mobile`) VALUES
('alanjmckenna', 't1234s', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', 'Dublin', '9988377', '856625567'),
('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', 'Dublin', '8887889', '876654456'),
('tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', 'Dublin', '9983747', '876738782'),
('user001', 'Library001!', 'Amelia', 'Byrne', '1 River Road', 'Rathmines', 'Dublin', 'Dublin', '010000001', '085000001'),
('user002', 'Library002!', 'Ben', 'Murphy', '2 Oak Avenue', 'Blackrock', 'Dublin', 'Dublin', '010000002', '085000002'),
('user003', 'Library003!', 'Cara', 'Walsh', '3 Main Street', 'Swords', 'Dublin', 'Dublin', '010000003', '085000003'),
('user004', 'Library004!', 'Daniel', 'Ryan', '4 Church Road', 'Bray', 'Wicklow', 'Wicklow', '010000004', '085000004'),
('user005', 'Library005!', 'Ella', 'Doyle', '5 Harbour View', 'Galway City', 'Galway', 'Galway', '010000005', '085000005'),
('user006', 'Library006!', 'Finn', 'Kelly', '6 College Road', 'Cork City', 'Cork', 'Cork', '010000006', '085000006'),
('user007', 'Library007!', 'Grace', 'OBrien', '7 Park Lane', 'Limerick City', 'Limerick', 'Limerick', '010000007', '085000007'),
('user008', 'Library008!', 'Harry', 'Nolan', '8 Station Road', 'Waterford City', 'Waterford', 'Waterford', '010000008', '085000008'),
('user009', 'Library009!', 'Isla', 'Kavanagh', '9 Market Square', 'Kilkenny Town', 'Kilkenny', 'Kilkenny', '010000009', '085000009'),
('user010', 'Library010!', 'Jack', 'Foley', '10 Green Road', 'Navan', 'Meath', 'Meath', '010000010', '085000010'),
('user011', 'Library011!', 'Katie', 'Quinn', '11 Castle Street', 'Naas', 'Kildare', 'Kildare', '010000011', '085000011'),
('user012', 'Library012!', 'Liam', 'Dunne', '12 Hill Road', 'Drogheda', 'Louth', 'Louth', '010000012', '085000012'),
('user013', 'Library013!', 'Mia', 'Hughes', '13 Lakeside', 'Athlone', 'Westmeath', 'Westmeath', '010000013', '085000013'),
('user014', 'Library014!', 'Noah', 'Murray', '14 Forest Road', 'Letterkenny', 'Donegal', 'Donegal', '010000014', '085000014'),
('user015', 'Library015!', 'Orla', 'Power', '15 Mill Road', 'Carlow Town', 'Carlow', 'Carlow', '010000015', '085000015'),
('user016', 'Library016!', 'Paul', 'Kennedy', '16 Valley Road', 'Ennis', 'Clare', 'Clare', '010000016', '085000016'),
('user017', 'Library017!', 'Ruby', 'Mahon', '17 Seaview', 'Tralee', 'Kerry', 'Kerry', '010000017', '085000017'),
('user018', 'Library018!', 'Sam', 'Healy', '18 Church Lane', 'Sligo Town', 'Sligo', 'Sligo', '010000018', '085000018'),
('user019', 'Library019!', 'Saoirse', 'Whelan', '19 Orchard Road', 'Wexford Town', 'Wexford', 'Wexford', '010000019', '085000019'),
('user020', 'Library020!', 'Theo', 'Clarke', '20 Garden Road', 'Mullingar', 'Westmeath', 'Westmeath', '010000020', '085000020');

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
