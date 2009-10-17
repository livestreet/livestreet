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
 * Добавление/удаление письма в избранное
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iType=getRequest('type',null,'post');
$idTalk=getRequest('idTalk',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	if (in_array($iType,array('1','0'))) {
		if ($oTalk=$oEngine->Talk_GetTalkById($idTalk)) {
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			$oFavouriteTalk=$oEngine->Talk_GetFavouriteTalk($oTalk->getId(),$oUserCurrent->getId());
			if (!$oFavouriteTalk and $iType) {
				$oFavouriteTalkNew=Engine::GetEntity('Favourite',
					array(
						'target_id'      => $oTalk->getId(),
						'target_type'    => 'talk',
						'user_id'        => $oUserCurrent->getId(),
						'target_publish' => '1'
					)
				);
				if ($oEngine->Talk_AddFavouriteTalk($oFavouriteTalkNew)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('talk_favourite_add_ok');
					$bState=true;
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}
			if (!$oFavouriteTalk and !$iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('talk_favourite_add_no');
			}
			if ($oFavouriteTalk and $iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('talk_favourite_add_already');
			}
			if ($oFavouriteTalk and !$iType) {
				if ($oEngine->Talk_DeleteFavouriteTalk($oFavouriteTalk)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('talk_favourite_del_ok');
					$bState=false;
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('system_error');
		}
	} else {
		$sMsgTitle=$oEngine->Lang_Get('error');
		$sMsg=$oEngine->Lang_Get('system_error');
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