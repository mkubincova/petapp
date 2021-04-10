/* Create db and table */
CREATE DATABASE api_db;

CREATE TABLE `api_db`.`petfacts` (
    `factID` INT NOT NULL AUTO_INCREMENT, 
    `text` VARCHAR(500) NOT NULL, 
    PRIMARY KEY (`factID`)) ENGINE = InnoDB;

/* Starter data */
INSERT INTO petfacts (text) VALUES 
("Forty-five percent of U.S. dogs sleep in their owner’s bed."),
("Flamingos are not pink. They are born grey, their diet of brine shrimp and blue green algae contains a natural pink dye called canthaxanthin that makes their feathers pink."),
("Hummingbirds are the only known birds that can also fly backwards."),
("Pet ownership was historically a sign of wealth and free time."),
("Dogs were the first domesticated animal and therefore, most likely, our human ancestors' first pet. Historians believe the first domesticated dogs became pets in the Paleolithic Era."),
("Pet insurance began in Sweden; half of that nation’s pets are now insured."),
("Dolphins use toxic pufferfish to ‘get high’."),
("Koalas can sleep for up to 22 hours a day."),
("A group of parrots is known as a pandemonium."),
("The shape of a dog’s face suggests its longevity: A long face means a longer life."),
("There is an average of 50,000 spiders per acre in green areas."),
("The word “pet” comes from the Middle English word “petty,” meaning “small.”"),
("Domestic cats spend about 70 percent of the day sleeping. And 15 percent of the day grooming."),
("Meows are not innate cat language—they developed them to communicate with humans."),
("Hamsters are intelligent creatures who can even learn their name. If you talk to your hamster and use their name frequently enough to get them used to hearing it, they might even learn to come when called.")