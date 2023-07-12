DROP DATABASE lovejoy;
CREATE DATABASE lovejoy;
USE lovejoy;
CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneno` varchar(13) NOT NULL,
  `forename` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `verification` varchar(12) NOT NULL,
  `admin` boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE `evaluations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` longblob,
  `imagetype` varchar(4),
  `accID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT `accounts_evaluations_ID` FOREIGN KEY (`accID`) REFERENCES accounts(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
