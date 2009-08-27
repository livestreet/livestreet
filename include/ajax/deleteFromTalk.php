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
 * Добавление/удаление пользователя в BlackList
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idTarget=@$_REQUEST['idTarget'];
$idTalk=@$_REQUEST['idTalk'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	if ($oUserTarget=$oEngine->User_GetUserById($idTarget)) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if(($oTalk=$oEngine->Talk_GetTalkById($idTalk)) 
			&& ($oTalk->getUserId()==$oUserCurrent->getId()) ) {
			$aTalkUsers=$oTalk->getTalkUsers();		
			if(!isset($aTalkUsers[$idTarget]) 
					|| !$aTalkUsers[$idTarget]->getUserActive()!=LsTalk::TALK_USER_DELETE_BY_SELF) {
				if ($oEngine->Talk_DeleteTalkUserByArray($idTalk,$idTarget,LsTalk::TALK_USER_DELETE_BY_AUTHOR)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('talk_speaker_delete_ok',array('login'=>$oUserTarget->getLogin()));
					$bState=true;
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			} else {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('talk_speaker_user_not_found',array('login'=>$oUserTarget->getLogin()));				
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('talk_not_found');			
		}
	} else {
		$sMsgTitle=$oEngine->Lang_Get('error');
		$sMsg=$oEngine->Lang_Get('user_not_found_by_id',array('id'=>$idTarget));
	}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$GLOBALS['_RESULT'] = array(
	"bStateError" => $bStateError,
	"bState"      => $bState,
	"sMsgTitle"   => $sMsgTitle,
	"sMsg"        => $sMsg,
);

?>