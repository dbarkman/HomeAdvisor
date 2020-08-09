SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `homeadvisor` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `homeadvisor`;

CREATE TABLE `businesses` (
  `id` int(8) NOT NULL,
  `businessName` varchar(64) NOT NULL,
  `addressLine1` varchar(64) NOT NULL,
  `addressLine2` varchar(64) NOT NULL,
  `city` varchar(64) NOT NULL,
  `stateAbbr` varchar(8) NOT NULL,
  `postal` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `businessesOperatingCities` (
  `id` int(8) NOT NULL,
  `businessId` int(8) NOT NULL,
  `operatingCityId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `businessesWorkTypes` (
  `id` int(8) NOT NULL,
  `businessId` int(8) NOT NULL,
  `workTypeId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `businessHours` (
  `id` int(8) NOT NULL,
  `businessId` int(8) NOT NULL,
  `dayOfWeek` varchar(16) NOT NULL,
  `open` int(8) NOT NULL,
  `close` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `operatingCities` (
  `id` int(8) NOT NULL,
  `city` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `reviews` (
  `id` int(8) NOT NULL,
  `businessId` int(8) NOT NULL,
  `ratingScore` float NOT NULL,
  `customerComment` varchar(1024) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `workTypes` (
  `id` int(8) NOT NULL,
  `workType` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`businessName`);

ALTER TABLE `businessesOperatingCities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `businessId` (`businessId`),
  ADD KEY `operatingCityId` (`operatingCityId`);

ALTER TABLE `businessesWorkTypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `businessId` (`businessId`),
  ADD KEY `workTypeId` (`workTypeId`);

ALTER TABLE `businessHours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `businessId` (`businessId`);

ALTER TABLE `operatingCities`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `businessId` (`businessId`);

ALTER TABLE `workTypes`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `businesses`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `businessesOperatingCities`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `businessesWorkTypes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `businessHours`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `operatingCities`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reviews`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

ALTER TABLE `workTypes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;


ALTER TABLE `businessesOperatingCities`
  ADD CONSTRAINT `businessesOperatingCities_ibfk_1` FOREIGN KEY (`businessId`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `businessesOperatingCities_ibfk_2` FOREIGN KEY (`operatingCityId`) REFERENCES `operatingCities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `businessesWorkTypes`
  ADD CONSTRAINT `businessesWorkTypes_ibfk_1` FOREIGN KEY (`businessId`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `businessesWorkTypes_ibfk_2` FOREIGN KEY (`workTypeId`) REFERENCES `workTypes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `businessHours`
  ADD CONSTRAINT `businessHours_ibfk_1` FOREIGN KEY (`businessId`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`businessId`) REFERENCES `businesses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

GRANT USAGE ON *.* TO 'homeadvisor'@'localhost' IDENTIFIED BY PASSWORD '*97255865F688F404EA4057F0F475F0A97E0739DB';
GRANT ALL PRIVILEGES ON `homeadvisor`.* TO 'homeadvisor'@'localhost';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

