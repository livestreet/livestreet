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
 * Добавление/удаление комментария в избранное
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iType=getRequest('type',null,'post');
$idComment=getRequest('idComment',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	if (in_array($iType,array('1','0'))) {
		if ($oComment=$oEngine->Comment_GetCommentById($idComment)) {
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			$oFavouriteComment=$oEngine->Comment_GetFavouriteComment($oComment->getId(),$oUserCurrent->getId());
			if (!$oFavouriteComment and $iType) {
				$oFavouriteCommentNew=Engine::GetEntity('Favourite',
					array(
						'target_id'      => $oComment->getId(),
						'target_type'    => 'comment',
						'user_id'        => $oUserCurrent->getId(),
						'target_publish' => $oComment->getPublish()
					)
				);
				if ($oEngine->Comment_AddFavouriteComment($oFavouriteCommentNew)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('comment_favourite_add_ok');
					$bState=true;
				} else {
					$sMsgTitle=$oEngine->Lang_Get('error');
					$sMsg=$oEngine->Lang_Get('system_error');
				}
			}
			if (!$oFavouriteComment and !$iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('comment_favourite_add_no');
			}
			if ($oFavouriteComment and $iType) {
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('comment_favourite_add_already');
			}
			if ($oFavouriteComment and !$iType) {
				if ($oEngine->Comment_DeleteFavouriteComment($oFavouriteComment)) {
					$bStateError=false;
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('comment_favourite_del_ok');
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