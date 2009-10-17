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
 * Автоподстановка логина юзеров
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

header('Content-Type: text/html; charset=utf-8');

if (!$sUser=getRequest('value',null,'post')) {
	exit();
}

if ($sUser!='') {
	$aUsers=$oEngine->User_GetUsersByLoginLike($sUser,10);
	foreach ($aUsers as $oUser) {
		echo('<li>'.$oUser->getLogin().'</li>');
	}
}
?>