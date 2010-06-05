CREATE TABLE IF NOT EXISTS `prefix_page` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_pid` int(11) unsigned DEFAULT NULL,
  `page_url` varchar(50) NOT NULL,
  `page_url_full` varchar(254) NOT NULL,
  `page_title` varchar(200) NOT NULL,
  `page_text` text NOT NULL,
  `page_date_add` datetime NOT NULL,
  `page_date_edit` datetime DEFAULT NULL,
  `page_seo_keywords` varchar(250) DEFAULT NULL,
  `page_seo_description` varchar(250) DEFAULT NULL,
  `page_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `page_main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_sort` int(11) NOT NULL,
  PRIMARY KEY (`page_id`),
  KEY `page_pid` (`page_pid`),
  KEY `page_url_full` (`page_url_full`,`page_active`),
  KEY `page_title` (`page_title`),
  KEY `page_sort` (`page_sort`),
  KEY `page_main` (`page_main`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `prefix_page` (`page_id`, `page_pid`, `page_url`, `page_url_full`, `page_title`, `page_text`, `page_date_add`, `page_date_edit`, `page_seo_keywords`, `page_seo_description`, `page_active`, `page_main`, `page_sort`) VALUES
(1, NULL, 'about', 'about', 'about', 'edit this page http://yousite/page/admin/', '2010-06-06 02:29:28', NULL, '', '', 1, 1, 1);