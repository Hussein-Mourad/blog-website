-- MySQL Script generated by MySQL Workbench
-- Tue Jul 18 21:33:55 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema blogs
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `blogs` ;

-- -----------------------------------------------------
-- Schema blogs
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blogs` DEFAULT CHARACTER SET utf8 ;
USE `blogs` ;

-- -----------------------------------------------------
-- Table `blogs`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`users` ;

CREATE TABLE IF NOT EXISTS `blogs`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(255) NOT NULL,
  `lastName` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('regular', 'admin') NOT NULL DEFAULT 'regular',
  `phone` VARCHAR(255) NOT NULL,
  `picture` VARCHAR(255) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `phone_UNIQUE` (`phone` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blogs`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`posts` ;

CREATE TABLE IF NOT EXISTS `blogs`.`posts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `authorId` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT now(),
  `updatedAt` DATETIME NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  INDEX `fk_posts_users_idx` (`authorId` ASC) ,
  CONSTRAINT `fk_posts_users`
    FOREIGN KEY (`authorId`)
    REFERENCES `blogs`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blogs`.`comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`comments` ;

CREATE TABLE IF NOT EXISTS `blogs`.`comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `postId` INT NOT NULL,
  `userId` INT NOT NULL,
  `parentId` INT NULL,
  `content` TEXT NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT now(),
  `updatedAt` DATETIME NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  INDEX `fk_comments_posts1_idx` (`postId` ASC) ,
  INDEX `fk_comments_comments1_idx` (`parentId` ASC) ,
  INDEX `fk_comments_users1_idx` (`userId` ASC) ,
  CONSTRAINT `fk_comments_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `blogs`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_comments1`
    FOREIGN KEY (`parentId`)
    REFERENCES `blogs`.`comments` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`userId`)
    REFERENCES `blogs`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blogs`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`categories` ;

CREATE TABLE IF NOT EXISTS `blogs`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blogs`.`posts_categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`posts_categories` ;

CREATE TABLE IF NOT EXISTS `blogs`.`posts_categories` (
  `categoryId` INT NOT NULL,
  `postId` INT NOT NULL,
  PRIMARY KEY (`categoryId`, `postId`),
  INDEX `fk_categories_has_posts_posts1_idx` (`postId` ASC) ,
  INDEX `fk_categories_has_posts_categories1_idx` (`categoryId` ASC) ,
  CONSTRAINT `fk_categories_has_posts_categories1`
    FOREIGN KEY (`categoryId`)
    REFERENCES `blogs`.`categories` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_categories_has_posts_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `blogs`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blogs`.`reactions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `blogs`.`reactions` ;

CREATE TABLE IF NOT EXISTS `blogs`.`reactions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` ENUM('like', 'haha', 'love', 'sad', 'angry') NOT NULL,
  `postId` INT NOT NULL,
  `userId` INT NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`, `postId`, `userId`),
  INDEX `fk_reactions_posts1_idx` (`postId` ASC) ,
  INDEX `fk_reactions_users1_idx` (`userId` ASC) ,
  CONSTRAINT `fk_reactions_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `blogs`.`posts` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_reactions_users1`
    FOREIGN KEY (`userId`)
    REFERENCES `blogs`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `blogs`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`users` (`id`, `firstName`, `lastName`, `email`, `password`, `role`, `phone`, `picture`, `createdAt`) VALUES (1, 'hussein', 'kassem', 'husseinmouradkassem9901@gmail.com', '123456', 'admin', '010088003724', NULL, DEFAULT);
INSERT INTO `blogs`.`users` (`id`, `firstName`, `lastName`, `email`, `password`, `role`, `phone`, `picture`, `createdAt`) VALUES (2, 'hussein', 'mourad', 'husseinmourad@gmail.com', '123456', 'regular', '010088349049', NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `blogs`.`posts`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`posts` (`id`, `authorId`, `title`, `content`, `createdAt`, `updatedAt`) VALUES (1, 1, 'Test Blog 1', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', DEFAULT, DEFAULT);
INSERT INTO `blogs`.`posts` (`id`, `authorId`, `title`, `content`, `createdAt`, `updatedAt`) VALUES (2, 1, 'Test Blog 2', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', DEFAULT, DEFAULT);
INSERT INTO `blogs`.`posts` (`id`, `authorId`, `title`, `content`, `createdAt`, `updatedAt`) VALUES (3, 2, 'Test Blog 3', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', DEFAULT, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `blogs`.`comments`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`comments` (`id`, `postId`, `userId`, `parentId`, `content`, `createdAt`, `updatedAt`) VALUES (1 , 1, 1, NULL, 'Bad Blog', DEFAULT, DEFAULT);
INSERT INTO `blogs`.`comments` (`id`, `postId`, `userId`, `parentId`, `content`, `createdAt`, `updatedAt`) VALUES (2, 2, 1, NULL, 'Cool blog', '', '');
INSERT INTO `blogs`.`comments` (`id`, `postId`, `userId`, `parentId`, `content`, `createdAt`, `updatedAt`) VALUES (3, 1, 1, 1, 'Yes its very bad', '', '');

COMMIT;


-- -----------------------------------------------------
-- Data for table `blogs`.`categories`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`categories` (`id`, `name`) VALUES (1, 'Sports');
INSERT INTO `blogs`.`categories` (`id`, `name`) VALUES (2, 'Technology');
INSERT INTO `blogs`.`categories` (`id`, `name`) VALUES (3, 'Religion');

COMMIT;


-- -----------------------------------------------------
-- Data for table `blogs`.`posts_categories`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`posts_categories` (`categoryId`, `postId`) VALUES (1, 1);
INSERT INTO `blogs`.`posts_categories` (`categoryId`, `postId`) VALUES (2, 2);
INSERT INTO `blogs`.`posts_categories` (`categoryId`, `postId`) VALUES (3, 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `blogs`.`reactions`
-- -----------------------------------------------------
START TRANSACTION;
USE `blogs`;
INSERT INTO `blogs`.`reactions` (`id`, `type`, `postId`, `userId`, `createdAt`) VALUES (1, 'like', 1, 1, DEFAULT);
INSERT INTO `blogs`.`reactions` (`id`, `type`, `postId`, `userId`, `createdAt`) VALUES (2, 'love', 2, 1, DEFAULT);
INSERT INTO `blogs`.`reactions` (`id`, `type`, `postId`, `userId`, `createdAt`) VALUES (3, 'haha', 3, 1, DEFAULT);
INSERT INTO `blogs`.`reactions` (`id`, `type`, `postId`, `userId`, `createdAt`) VALUES (4, 'sad', 3, 2, DEFAULT);
INSERT INTO `blogs`.`reactions` (`id`, `type`, `postId`, `userId`, `createdAt`) VALUES (5, 'angry', 1, 2, DEFAULT);

COMMIT;

