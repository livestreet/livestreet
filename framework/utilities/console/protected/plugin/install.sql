--
-- SQL, которые надо выполнить движку при активации плагина админом. Вызывается на исполнение ВРУЧНУЮ в /plugins/PluginAbcplugin.class.php в методе Activate()
-- Например:

-- CREATE TABLE IF NOT EXISTS `prefix_tablename` (
--  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--  `page_pid` int(11) unsigned DEFAULT NULL,
--  PRIMARY KEY (`page_id`),
--  KEY `page_pid` (`page_pid`),
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
