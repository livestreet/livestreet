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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=false;
$sMsg='';
$sMsgTitle='';
$iCountTalkNew=0;

if (!$oEngine->User_IsAuthorization()) {
	$bStateError = true;
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
} elseif($oUser = $oEngine->User_GetUserCurrent()){
	$iCountTalkNew = $oEngine->Talk_GetCountTalkNew($oUser->getId());	
} else {
	$bStateError = true;
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('system_error');	
}

$GLOBALS['_RESULT'] = array(
	"bStateError"   => $bStateError,
	"sMsgTitle"     => $sMsgTitle,
	"sMsg"          => $sMsg,
	"iCountTalkNew" => $iCountTalkNew,
);

?>