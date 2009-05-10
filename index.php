<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('SYS_HACKER_CONSOLE',false);
header('Content-Type: text/html; charset=utf-8');

$t1=microtime(true);

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
chdir(dirname(__FILE__));
require_once("./config/config.php");
require_once("./classes/engine/Router.class.php");

$oRouter=Router::getInstance();
$oRouter->Exec();
$aStats=$oRouter->getStats();
$t2=microtime(true);
?>


<? 
$oUser=$oRouter->User_GetUserCurrent();
if (Router::GetIsShowStats() and $oUser and $oUser->isAdministrator()) { 
?>
<fieldset>
<legend>Статистика выполнения</legend>
<table>
	<tr align="top">
		<td align="top">
		<ul>
	<li>
	<b>MySql</b> <br>
	&nbsp;&nbsp;&nbsp;запросов: <?echo($aStats['sql']['count']);?><br>
	&nbsp;&nbsp;&nbsp;время: <?echo($aStats['sql']['time']);?><br><br><br>
	</li>
	</ul>
		</td>
		<td>
		<ul>
	<li>
	<b>Cache</b> <br>
	&nbsp;&nbsp;&nbsp;запросов: <?echo($aStats['cache']['count']);?><br>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; set: <?echo($aStats['cache']['count_set']);?><br>
	&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; get: <?echo($aStats['cache']['count_get']);?><br>
	&nbsp;&nbsp;&nbsp;время: <?echo(round($aStats['cache']['time'],5));?>
	</li>
	</ul>
		</td>
		<td align="top">
		<ul>
	<li>
	<b>PHP</b> <br>	
	&nbsp;&nbsp;&nbsp;загрузка модулей:<?echo($aStats['engine']['time_load_module']);?><br>
	&nbsp;&nbsp;&nbsp;общее время:<?echo(round($t2-$t1,3));?><br><br><br>
	</li>
	</ul>
		</td>
	</tr>
</table>
</fieldset>
<? } ?>