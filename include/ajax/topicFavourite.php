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
 * Добавление/удаление топика в избранное
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iType=getRequest('type',null,'post');
$idTopic=getRequest('idTopic',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	if (in_array($iType,array('1','0'))) {
		if ($oTopic=$oEngine->Topic_GetTopicById($idTopic)) {
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			$oFavouriteTopic=$oEngine->Topic_GetFavouriteTopic($oTopic->getId(),$oUserCurrent->getId());
			if (!$oFavouriteTopic and $iType) {
				$oFavouriteTopicNew=Engine::GetEntity('Favourite',
					array(
						'target_id'      => $oTopic->getId(),
						'user_id'        => $oUserCurrent->getId(),
						'target_type'    => 'topic',
						'target_publish' => $oTopic->getPublish()
					)
				);
				if ($oEngine->Topic_AddFavouriteTopic($oFavouriteTopicNew)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('topic_favourite_add_ok');
					$bState=true;
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}
			if (!$oFavouriteTopic and !$iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('topic_favourite_add_no');
			}
			if ($oFavouriteTopic and $iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('topic_favourite_add_already');
			}
			if ($oFavouriteTopic and !$iType) {
				if ($oEngine->Topic_DeleteFavouriteTopic($oFavouriteTopic)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('topic_favourite_del_ok');
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
"bStateError"     => $bStateError,
"bState"   => $bState,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>