/* commands to create all tables, primary keys etc. */
/* paste in MySql/Heidi to initialize the database */

CREATE DATABASE petapp;

CREATE TABLE `petapp`.`user` ( `userID` INT NOT NULL AUTO_INCREMENT , `userType` VARCHAR(10) NOT NULL , `username` VARCHAR(32) NOT NULL , `password` VARCHAR(32) NOT NULL , `firstname` VARCHAR(32) NOT NULL , `lastname` VARCHAR(32) NOT NULL , `email` VARCHAR(32) NOT NULL , PRIMARY KEY (`userID`)) ENGINE = InnoDB;

CREATE TABLE `petapp`.`pet` ( `petID` INT(11) NOT NULL , `name` VARCHAR(32) NOT NULL , `species` VARCHAR(32) NOT NULL , `breed` VARCHAR(32) NOT NULL , `birthday` DATE NOT NULL , `imgUrl` VARCHAR(500) NOT NULL , `likes` VARCHAR(200) NOT NULL , `dislikes` VARCHAR(200) NOT NULL , `otherInformation` VARCHAR(200) NOT NULL ) ENGINE = InnoDB;