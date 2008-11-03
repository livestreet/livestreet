<?
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
$fp = fopen("./update/update_0.1.2_to_0.2.sql", "r");
if (!$fp) {
	die("Не найден SQL файл - update_0.1.2_to_0.2.sql");
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
	if (isset($row['topic_text'])) {
		$sql2 = "REPLACE INTO ".DB_TABLE_TOPIC_CONTENT." 
			(topic_id,topic_text,topic_text_short,topic_text_source)
			values(".$row['topic_id'].",'".mysql_escape_string($row['topic_text'])."','".mysql_escape_string($row['topic_text_short'])."','".mysql_escape_string($row['topic_text_source'])."')		
	
		";		
		mysql_query($sql2,$link);
	}	
}


/**
 * Завершаем конвертацию БД - удаляем лишние колонки из таблицы топиков.
 * Внимание! Если не отработала конвертация топиков в новую структуру, то будет потерянно всё содержание всех топиков!!
 */
$sSql="
ALTER TABLE `".DB_TABLE_TOPIC."` DROP `topic_text`  ;
ALTER TABLE `".DB_TABLE_TOPIC."` DROP `topic_text_short`  ;
ALTER TABLE `".DB_TABLE_TOPIC."` DROP `topic_text_source`  ;
ALTER TABLE `".DB_TABLE_TOPIC."` CHANGE `topic_tags` `topic_tags` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'через запятую перечислены теги' ;
";
$aSqlList=explode(';',$sSql);
foreach ($aSqlList as $s) {
	if (trim($s)!='') {
		if (!mysql_query($s,$link)) {
			var_dump(mysql_error($link));
		}
	}
}



/**
 * Конвертируем комментариии в новую структуру
 * Если комментариев много, то может занять много времени
 */
$sql = "SELECT res.* FROM (		

				SELECT 					
					c.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login				
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t,
					".DB_TABLE_USER." as u,					
					".DB_TABLE_BLOG." as b,
					".DB_TABLE_USER." as u_owner 
				WHERE 	
					c.comment_id=(SELECT comment_id FROM ".DB_TABLE_TOPIC_COMMENT." WHERE topic_id=t.topic_id AND t.topic_publish=1 ORDER BY comment_date DESC LIMIT 0,1)
					AND
					c.comment_delete = 0
					AND				
					c.topic_id=t.topic_id
					AND
					t.topic_publish = 1
					AND			
					c.user_id=u.user_id					
					AND
					t.blog_id=b.blog_id
					AND
					b.user_owner_id=u_owner.user_id				
				ORDER by c.comment_date desc limit 0, 50 
				
				) as res
		ORDER BY comment_date asc	
					";
$res=mysql_query($sql,$link);
while ($row=mysql_fetch_assoc($res)) {	
	$sql2 = "REPLACE INTO ".DB_TABLE_TOPIC_COMMENT_ONLINE." 
			SET topic_id = ".$row['topic_id']." ,comment_id = ".$row['comment_id']."
	";
	mysql_query($sql2,$link);
}



require_once("./config/config.ajax.php");
/**
 * конвертируем страны и города в новую структуру
 */
$aData=$oEngine->User_GetUsersRating('good',0,1,10000);
$aUsers=$aData['collection'];
foreach ($aUsers as $oUser) {
	/**
	* Добавляем страну
	*/
	if ($oUser->getProfileCountry()) {
		if (!($oCountry=$oEngine->User_GetCountryByName($oUser->getProfileCountry()))) {
			$oCountry=new UserEntity_Country();
			$oCountry->setName($oUser->getProfileCountry());
			$oEngine->User_AddCountry($oCountry);
		}
		$oEngine->User_SetCountryUser($oCountry->getId(),$oUser->getId());
	}
	/**
	* Добавляем город
	*/
	if ($oUser->getProfileCity()) {
		if (!($oCity=$oEngine->User_GetCityByName($oUser->getProfileCity()))) {
			$oCity=new UserEntity_City();
			$oCity->setName($oUser->getProfileCity());
			$oEngine->User_AddCity($oCity);
		}
		$oEngine->User_SetCityUser($oCity->getId(),$oUser->getId());
	}
}
$aData=$oEngine->User_GetUsersRating('bad',0,1,10000);
$aUsers=$aData['collection'];
foreach ($aUsers as $oUser) {
	/**
	* Добавляем страну
	*/
	if ($oUser->getProfileCountry()) {
		if (!($oCountry=$oEngine->User_GetCountryByName($oUser->getProfileCountry()))) {
			$oCountry=new UserEntity_Country();
			$oCountry->setName($oUser->getProfileCountry());
			$oEngine->User_AddCountry($oCountry);
		}
		$oEngine->User_SetCountryUser($oCountry->getId(),$oUser->getId());
	}
	/**
	* Добавляем город
	*/
	if ($oUser->getProfileCity()) {
		if (!($oCity=$oEngine->User_GetCityByName($oUser->getProfileCity()))) {
			$oCity=new UserEntity_City();
			$oCity->setName($oUser->getProfileCity());
			$oEngine->User_AddCity($oCity);
		}
		$oEngine->User_SetCityUser($oCity->getId(),$oUser->getId());
	}
}
?>
Если никакие ошибки не повылазили, значит апдейт на новую версию прошел успешно. Поздравляем!