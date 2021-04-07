/* commands to create all tables, primary keys etc. */
/* paste in MySql/Heidi to initialize the database */

CREATE DATABASE petapp;

CREATE TABLE `petapp`.`user` (
    `userID` INT NOT NULL AUTO_INCREMENT, 
    `userType` VARCHAR(10) NOT NULL, 
    `username` VARCHAR(32) NOT NULL, 
    `password` VARCHAR(32) NOT NULL, 
    `firstname` VARCHAR(32) NOT NULL, 
    `lastname` VARCHAR(32) NOT NULL, 
    `email` VARCHAR(32) NOT NULL, 
    PRIMARY KEY (`userID`)) ENGINE = InnoDB;

CREATE TABLE `petapp`.`pet` ( 
    `petID` INT(11) NOT NULL AUTO_INCREMENT, 
    `name` VARCHAR(64) NOT NULL, 
    `breed` VARCHAR(64) NOT NULL,
    `species` VARCHAR(64) NULL,
    `birthday` DATE NULL,
    `imgUrl` VARCHAR(500) NULL, 
    `likes` VARCHAR(200) NULL, 
    `dislikes` VARCHAR(200) NULL, 
    `otherInformation` VARCHAR(500) NULL, 
    PRIMARY KEY (`petID`)) ENGINE = InnoDB;

CREATE TABLE `petapp`.`user_pet` (
    `userID` INT(11) NOT NULL,
    `petID` INT(11) NOT NULL,
    FOREIGN KEY (userID) REFERENCES user(userID),
    FOREIGN KEY (petID) REFERENCES pet(petID)
);

CREATE TABLE `petapp`.`animal` ( 
    `animalID` INT(11) NOT NULL AUTO_INCREMENT, 
    `species` VARCHAR(64) NOT NULL, 
    `facts` VARCHAR(500) NOT NULL, 
    `characteristics` VARCHAR(500) NULL, 
    `averageLifespan` VARCHAR(20) NULL, 
    `forbiddenFood` VARCHAR(500) NULL, 
    `imgUrl` VARCHAR(500) NULL, 
    PRIMARY KEY (`animalID`)) ENGINE = InnoDB;