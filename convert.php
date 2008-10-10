<?
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_time_limit(0);

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
chdir(dirname(__FILE__));

require_once("./config/config.table.php");

$aConfig=include("./config/config.db.php");


$link=mysql_connect($aConfig['host'],$aConfig['user'],$aConfig['pass']);
mysql_select_db($aConfig['dbname'],$link);
mysql_query("set character_set_client='utf8'",$link);
mysql_query("set character_set_results='utf8'",$link);
mysql_query("set collation_connection='utf8_bin'",$link);

/**
 * Конвертирует топики из старой структуры в новую
 */

$sql = "SELECT 
			*										
		FROM 			
			".DB_TABLE_TOPIC." as t	;	
		";
$res=mysql_query($sql,$link);
while ($row=mysql_fetch_assoc($res)) {
	//var_dump($row);
	$sql2 = "INSERT INTO ".DB_TABLE_TOPIC_CONTENT." 
			(topic_id,topic_text,topic_text_short,topic_text_source)
			values(".$row['topic_id'].",'".mysql_escape_string($row['topic_text'])."','".mysql_escape_string($row['topic_text_short'])."','".mysql_escape_string($row['topic_text_source'])."')		
	
	";
	mysql_query($sql2,$link);
}
?>