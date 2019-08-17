
-- CREATE DATABASE IF NOT EXISTS `list_barang` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci	;
-- USE `list_barang`;

-- Delete list_barang table
-- DROP TABLE `list_barang`;

-- Create list_barang table
CREATE TABLE IF NOT EXISTS `list_barang` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`nama_barang` varchar(360) NOT NULL ,
	`foto_barang` longtext NOT NULL ,
	`kondisi_posisi` text NOT NULL ,
	PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


