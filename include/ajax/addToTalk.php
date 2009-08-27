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
$idTalk=@$_REQUEST['idTalk'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if(($oTalk=$oEngine->Talk_GetTalkById($idTalk)) 
			&& ($oTalk->getUserId()==$oUserCurrent->getId()) ) {
			$aTalkUsers=$oTalk->getTalkUsers();
			$aUsers=explode(',',$sUsers);
			$aUserInBlacklist = $oEngine->Talk_GetBlacklistByTargetId($oUserCurrent->getId());			
			// для каждого пользователя проверяем блек-лист и наличие в толке
			foreach ($aUsers as $sUser) {
				$sUser=trim($sUser);			
				if ($sUser=='' or strtolower($sUser)==strtolower($oUserCurrent->getLogin())) {
					continue;
				}	
				if ( ($oUser=$oEngine->User_GetUserByLogin($sUser)) 
						&& ($oUser->getActivate()==1) ) {		
					if(!in_array($oUser->getId(),$aUserInBlacklist)) {
						if(!array_key_exists($oUser->getId(),$aTalkUsers)) {
							if (
								$oEngine->Talk_AddTalkUser(
									new TalkEntity_TalkUser(
										array(
											'talk_id'=>$idTalk,
											'user_id'=>$oUser->getId(),
											'date_last'=>null,
											'talk_user_active'=>'1'
										)
									)
								)
							) {
								$oEngine->Notify_SendTalkNew($oUser,$oUserCurrent,$oTalk);
								$aResult[]=array(
									'bStateError'=>false,
									'sMsgTitle'=>$oEngine->Lang_Get('attention'),
									'sMsg'=>$oEngine->Lang_Get('talk_speaker_add_ok',array('%%login%%',$sUser)),
									'sUserId'=>$oUser->getId(),
									'sUserLogin'=>$oUser->getLogin()
								);
								$bState=true;
							} else {
								$aResult[]=array(
									'bStateError'=>true,
									'sMsgTitle'=>$oEngine->Lang_Get('error'),
									'sMsg'=>$oEngine->Lang_Get('system_error')
								);
							}
						} else {
							$aResult[]=array(
								'bStateError'=>true,
								'sMsgTitle'=>$oEngine->Lang_Get('error'),
								'sMsg'=>$oEngine->Lang_Get('talk_speaker_user_already_exist',array('%%login%%',$sUser))
							);				
						}
					} else {
						$aResult[]=array(
							'bStateError'=>true,
							'sMsgTitle'=>$oEngine->Lang_Get('error'),
							'sMsg'=>$oEngine->Lang_Get('talk_user_in_blacklist',array('%%login%%',$sUser))
						);						
					}
				} else {
					$aResult[]=array(
						'bStateError'=>true,
						'sMsgTitle'=>$oEngine->Lang_Get('error'),
						'sMsg'=>$oEngine->Lang_Get('module_error_user_not_found',array('%%login%%',$sUser)),
						'sUserLogin'=>$sUser
					);
				}	
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('module_error_talk_not_found');			
		}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$GLOBALS['_RESULT'] = array(
	"bStat"       => $bState,
	"sMsgTitle"   => $sMsgTitle,
	"sMsg"        => $sMsg,
);
if($aResult){
	$GLOBALS['_RESULT']['aUsers']=$aResult;
}

?>