-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema tagq
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema tagq
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tagq` DEFAULT CHARACTER SET utf8 ;
USE `tagq` ;

-- -----------------------------------------------------
-- Table `tagq`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `username` VARCHAR(32) NOT NULL,
  `password` TEXT NOT NULL,
  `role` ENUM("User", "Vendor", "Admin") NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`personal_info`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`personal_info` (
  `user_id` INT NOT NULL,
  `first_name` VARCHAR(32) NOT NULL,
  `last_name` VARCHAR(32) NOT NULL,
  `address` VARCHAR(200) NOT NULL,
  `postal_code` VARCHAR(16) NOT NULL,
  `phone_number` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `info_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `tagq`.`users` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`items` (
  `item_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `description` VARCHAR(1024) NOT NULL,
  `price` INT(32) NOT NULL,
  `vendor_id` INT NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`item_id`),
  INDEX `listing_user_id_idx` (`vendor_id` ASC) ,
  CONSTRAINT `listing_user_id`
    FOREIGN KEY (`vendor_id`)
    REFERENCES `tagq`.`users` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`metadata`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`metadata` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `item_id` INT NOT NULL,
  `metadata_name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `metadata_item_id_idx` (`item_id` ASC) ,
  CONSTRAINT `metadata_item_id`
    FOREIGN KEY (`item_id`)
    REFERENCES `tagq`.`items` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`orders` (
  `order_id` INT NOT NULL AUTO_INCREMENT,
  `buyer_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `fulfilled` TINYINT NOT NULL,
  `ordered_at` DATETIME NOT NULL DEFAULT NOW(),
  `fulfilled_at` DATETIME NULL,
  PRIMARY KEY (`order_id`),
  INDEX `order_item_id_idx` (`item_id` ASC) ,
  INDEX `order_buyer_id_idx` (`buyer_id` ASC) ,
  CONSTRAINT `order_buyer_id`
    FOREIGN KEY (`buyer_id`)
    REFERENCES `tagq`.`users` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `order_item_id`
    FOREIGN KEY (`item_id`)
    REFERENCES `tagq`.`items` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`inventory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`inventory` (
  `part_id` INT NOT NULL AUTO_INCREMENT,
  `part_name` VARCHAR(64) NOT NULL,
  `sku` VARCHAR(64) NOT NULL,
  `stock` INT(32) NOT NULL,
  PRIMARY KEY (`part_id`),
  UNIQUE INDEX `part_name_UNIQUE` (`part_name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`reviews`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`reviews` (
  `review_id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `body` VARCHAR(256) NOT NULL,
  `rating` INT NOT NULL,
  `created_date` DATETIME NOT NULL DEFAULT NOW(),
  PRIMARY KEY (`review_id`),
  INDEX `review_order_id_idx` (`order_id` ASC) ,
  UNIQUE INDEX `order_id_UNIQUE` (`order_id` ASC) ,
  CONSTRAINT `review_order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `tagq`.`orders` (`order_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`item_inventory_usage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`item_inventory_usage` (
  `item_id` INT NOT NULL,
  `part_id` INT NOT NULL,
  `amount` INT NOT NULL,
  INDEX `part_id_idx` (`part_id` ASC) ,
  CONSTRAINT `item_id`
    FOREIGN KEY (`item_id`)
    REFERENCES `tagq`.`items` (`item_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `part_id`
    FOREIGN KEY (`part_id`)
    REFERENCES `tagq`.`inventory` (`part_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tagq`.`order_metadata`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tagq`.`order_metadata` (
  `order_id` INT NOT NULL,
  `value` VARCHAR(255) NOT NULL,
  `metadata_id` INT NOT NULL,
  INDEX `order_meta_order_id_idx` (`order_id` ASC) ,
  INDEX `order_meta_metadata_id_idx` (`metadata_id` ASC) ,
  CONSTRAINT `order_meta_order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `tagq`.`orders` (`order_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `order_meta_metadata_id`
    FOREIGN KEY (`metadata_id`)
    REFERENCES `tagq`.`metadata` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
