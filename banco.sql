-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema rfid2fa
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema rfid2fa
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `rfid2fa` DEFAULT CHARACTER SET utf8 ;
USE `rfid2fa` ;

-- -----------------------------------------------------
-- Table `rfid2fa`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rfid2fa`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rfid2fa`.`cartao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rfid2fa`.`cartao` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uid` VARCHAR(255) NOT NULL,
  `id_usuario` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uid_UNIQUE` (`uid` ASC) VISIBLE,
  INDEX `fk_usuario_cartao_idx` (`id_usuario` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_cartao`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `rfid2fa`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `rfid2fa`.`log_acesso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rfid2fa`.`log_acesso` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` TIMESTAMP NOT NULL,
  `sucesso` TINYINT NOT NULL,
  `id_usuario` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_log_acesso_usuario_idx` (`id_usuario` ASC) VISIBLE,
  CONSTRAINT `fk_log_acesso_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `rfid2fa`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `rfid2fa`.`leitura`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `rfid2fa`.`leitura` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` TIMESTAMP NOT NULL,
  `uid_cartao` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
