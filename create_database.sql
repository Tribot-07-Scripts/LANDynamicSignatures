CREATE TABLE `signature_data_yourscriptname` (
	`username` VARCHAR(50) NOT NULL DEFAULT '',
	`runtime` INT(15) NULL DEFAULT NULL,
	`var1` INT(10) UNSIGNED NULL DEFAULT NULL,
	`var2` INT(10) UNSIGNED NULL DEFAULT NULL,
	`var3` INT(10) UNSIGNED NULL DEFAULT NULL,
	`var4` INT(10) UNSIGNED NULL DEFAULT NULL,
	PRIMARY KEY (`username`),
	UNIQUE INDEX `username` (`username`)
)
COMMENT='Holds the data of the script users to be used for the creation of dynamic signatures'
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT