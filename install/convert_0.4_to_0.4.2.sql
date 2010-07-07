ALTER TABLE  `prefix_page` ADD  `page_main` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0',
ADD INDEX (  `page_main` );
ALTER TABLE  `prefix_page` ADD  `page_sort` INT NOT NULL ,
ADD INDEX (  `page_sort` );