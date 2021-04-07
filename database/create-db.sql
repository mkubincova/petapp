/* commands to create all tables, primary keys etc. */
/* paste in MySql/Heidi to initialize the database */

CREATE DATABASE petapp;

CREATE TABLE `petapp`.`user` ( `userID` INT NOT NULL AUTO_INCREMENT , `userType` VARCHAR(10) NOT NULL , `username` VARCHAR(32) NOT NULL , `password` VARCHAR(32) NOT NULL , `firstname` VARCHAR(32) NOT NULL , `lastname` VARCHAR(32) NOT NULL , `email` VARCHAR(32) NOT NULL , PRIMARY KEY (`userID`)) ENGINE = InnoDB;
