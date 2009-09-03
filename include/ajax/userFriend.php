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
$sAction=@$_REQUEST['sAction'];
$sUserText=@$_REQUEST['userText'];

if(!$sUserText) {
	$sUserText='';
}

$bStateError=true;
$sMsg='';
$sMsgTitle='';
$sToggleText='';
$bState=false;
if(in_array($sAction,array('del'))) {
	if ($oEngine->User_IsAuthorization()) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oUserCurrent->getId()!=$idUser) {
			if ($oUser=$oEngine->User_GetUserById($idUser)) {			
				$oFriend=$oEngine->User_GetFriend($oUser->getId(),$oUserCurrent->getId());
				switch($sAction) {
					case 'del': 
						if ($oFriend && ($oFriend->getFriendStatus() == LsUser::USER_FRIEND_ACCEPT+LsUser::USER_FRIEND_OFFER)) {
							if ($oEngine->User_DeleteFriend($oFriend)) {
								$bStateError=false;
								$sMsgTitle=$oEngine->Lang_Get('attention');
								$sMsg=$oEngine->Lang_Get('user_friend_del_ok');
								$bState=false;
								$sToggleText=$oEngine->Lang_Get('user_friend_add');
							} else {
								$sMsgTitle=$oEngine->Lang_Get('error');
								$sMsg=$oEngine->Lang_Get('system_error');
							}
						} else {	 								
							$sMsgTitle=$oEngine->Lang_Get('error');
							$sMsg=$oEngine->Lang_Get('user_friend_del_no');
						}						
						break;
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
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('system_error');
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"   => $bState,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"sToggleText"   => $sToggleText,
);

?>