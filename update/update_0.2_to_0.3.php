<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_time_limit(0);

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(__FILE__)));
chdir(dirname(dirname(__FILE__)));

require_once("./config/config.table.php");

$aConfig=include("./config/config.db.php");
$link=mysql_connect($aConfig['host'],$aConfig['user'],$aConfig['pass']);
mysql_select_db($aConfig['dbname'],$link);
mysql_query("set character_set_client='utf8'",$link);
mysql_query("set character_set_results='utf8'",$link);
mysql_query("set collation_connection='utf8_bin'",$link);

/**
 * Выполняем SQL для конвертации структуры БД
 */
$fp = fopen("./update/update_0.2_to_0.3.sql", "r");
if (!$fp) {
	die("Не найден SQL файл - update_0.2_to_0.3.sql");
}

$sSql = '';
while (!feof($fp)) {
  $sSql.=fread($fp, 1024*4);
}
fclose($fp);
if ($sSql!='') {
	$aSqlList=explode(';',$sSql);
	foreach ($aSqlList as $s) {
		if (trim($s)!='') {
			if (!mysql_query($s,$link)) {
				var_dump(mysql_error($link));
			}
		}
	}	
}
?>
Если никакие ошибки не повылазили, значит апдейт на новую версию прошел успешно. Поздравляем!