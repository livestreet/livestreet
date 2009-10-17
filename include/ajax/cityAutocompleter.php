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

/**
 * Автоподстановка города
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

header('Content-Type: text/html; charset=utf-8');

if (!$sCity=getRequest('value',null,'post')) {
	exit();
}

if ($sCity!='') {
	$aCity=$oEngine->User_GetCityByNameLike($sCity,10);
	foreach ($aCity as $oCity) {
		echo('<li>'.$oCity->getName().'</li>');
	}
}
?>