/**
 * サンプルプログラム用テーブル
 * @author Fea
 */

CREATE SCHEMA `redis_test`;

CREATE TABLE `redis_test`.`cards` (
  `id` INT NOT NULL,
  `name` VARCHAR(64) NULL,
  `race` TINYINT NULL,
  `attribute` TINYINT NULL,
  `hp` MEDIUMINT NULL,
  `mp` MEDIUMINT NULL,
  `str` SMALLINT NULL,
  `vit` SMALLINT NULL,
  `int` SMALLINT NULL,
  `mnd` SMALLINT NULL,
  `agi` SMALLINT NULL,
  `dex` SMALLINT NULL,
  `luk` SMALLINT NULL,
  `modified` DATETIME NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`));
