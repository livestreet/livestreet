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

if (!$sCountry=getRequest('value',null,'post')) {
	exit();
}

if ($sCountry!='') {
	$aCountry=$oEngine->User_GetCountryByNameLike($sCountry,10);
	foreach ($aCountry as $oCountry) {
		echo('<li>'.$oCountry->getName().'</li>');
	}
}
?>