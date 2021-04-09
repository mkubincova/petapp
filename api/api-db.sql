CREATE DATABASE api_db;

CREATE TABLE `api_db`.`petfacts` (
    `factID` INT NOT NULL AUTO_INCREMENT, 
    `text` VARCHAR(500) NOT NULL, 
    PRIMARY KEY (`factID`)) ENGINE = InnoDB;