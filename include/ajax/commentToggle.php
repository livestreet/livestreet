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
 * Удаление/восстановление комментария админом
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idComment=getRequest('idComment',null,'post');
$bStateError=true;
$bState='';
$sTextToggle='';
$sMsg='';
$sMsgTitle='';
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($oUserCurrent->isAdministrator()) {
		if ($oComment=$oEngine->Comment_GetCommentById($idComment)) {
			$oComment->setDelete(($oComment->getDelete()+1)%2);
			if ($oEngine->Comment_UpdateCommentStatus($oComment)) {
				$bStateError=false;
				$bState=(bool)$oComment->getDelete();
				$sMsgTitle=$oEngine->Lang_Get('attention');
				if ($bState) {
					$sMsg=$oEngine->Lang_Get('comment_delete_ok');
					$sTextToggle=$oEngine->Lang_Get('comment_repair');
				} else {
					$sMsg=$oEngine->Lang_Get('comment_repair_ok');
					$sTextToggle=$oEngine->Lang_Get('comment_delete');
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
		$sMsg=$oEngine->Lang_Get('not_access');
	}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"     => $bState,
"sTextToggle"     => $sTextToggle,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>