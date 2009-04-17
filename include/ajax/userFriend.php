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
 * Добавление/удаление друзей
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idUser=@$_REQUEST['idUser'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$sToggleText='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($oUserCurrent->getId()!=$idUser) {
		if ($oUser=$oEngine->User_GetUserById($idUser)) {			
			$oFrend=$oEngine->User_GetFrend($oUser->getId(),$oUserCurrent->getId());
			if (!$oFrend) {
				$oFrendNew=new UserEntity_Frend();
				$oFrendNew->setFrendId($oUser->getId());
				$oFrendNew->setUserId($oUserCurrent->getId());
				if ($oEngine->User_AddFrend($oFrendNew)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('user_friend_add_ok');
					$bState=true;
					$sToggleText=$oEngine->Lang_Get('user_friend_del');
					$oEngine->Notify_SendUserFriendNew($oUser,$oUserCurrent);
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}			
			if ($oFrend) {
				if ($oEngine->User_DeleteFrend($oFrend)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('user_friend_del_ok');
					$bState=false;
					$sToggleText=$oEngine->Lang_Get('user_friend_add');
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('user_friend_del_no');
		}
	} else {
		$sMsgTitle=$oEngine->Lang_Get('error');
		$sMsg=$oEngine->Lang_Get('user_friend_add_self');
	}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"   => $bState,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"sToggleText"   => $sToggleText,
);

?>