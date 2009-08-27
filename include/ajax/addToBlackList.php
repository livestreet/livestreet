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

$sUsers=@$_REQUEST['users'];
$bStateError=false;
$sMsg='';
$sMsgTitle='';
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	$aUsers=explode(',',$sUsers);
	$aUserBlacklist = $oEngine->Talk_GetBlacklistByUserId($oUserCurrent->getId());

	$aResult=array();
	foreach ($aUsers as $sUser) {
		$sUser=trim($sUser);			
		if ($sUser=='' or strtolower($sUser)==strtolower($oUserCurrent->getLogin())) {
			continue;
		}
		if ($oUser=$oEngine->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
			if(!isset($aUserBlacklist[$oUser->getId()])) {
				if($oEngine->Talk_AddUserToBlackList($oUser->getId(),$oUserCurrent->getId())) {
					$aResult[]=array(
						'bStateError'=>false,
						'sMsgTitle'=>$oEngine->Lang_Get('attention'),
						'sMsg'=>$oEngine->Lang_Get('talk_blacklist_add_ok',array('login'=>$sUser)),
						'sUserId'=>$oUser->getId(),
						'sUserLogin'=>$sUser
					);
				} else {
					$aResult[]=array(
						'bStateError'=>true,
						'sMsgTitle'=>$oEngine->Lang_Get('error'),
						'sMsg'=>$oEngine->Lang_Get('system_error'),
						'sUserLogin'=>$sUser
					);					
				}
			} else {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$oEngine->Lang_Get('error'),
					'sMsg'=>$oEngine->Lang_Get('talk_blacklist_user_already_have',array('login'=>$sUser)),
					'sUserLogin'=>$sUser
				);
				continue;
			}
		} else {
			$aResult[]=array(
				'bStateError'=>true,
				'sMsgTitle'=>$oEngine->Lang_Get('error'),
				'sMsg'=>$oEngine->Lang_Get('user_not_found',array('login'=>$sUser)),
				'sUserLogin'=>$sUser
			);
		}	
		
	}
} else {
	$bStateError=true;
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}


$GLOBALS['_RESULT'] = array(
	"bStateError" => $bStateError,
	"sMsgTitle"   => $sMsgTitle,
	"sMsg"        => $sMsg,
);
if($aResult){
	$GLOBALS['_RESULT']['aUsers']=$aResult;
}
?>