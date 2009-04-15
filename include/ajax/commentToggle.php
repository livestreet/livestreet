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

$idComment=@$_REQUEST['idComment'];
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
			if ($oEngine->Comment_UpdateTopicComment($oComment)) {
				$bStateError=false;
				$bState=(bool)$oComment->getDelete();
				$sMsgTitle='Отлично!';
				if ($bState) {
					$sMsg='Комментарий удален';
					$sTextToggle='Восстановить';
				} else {
					$sMsg='Комментарий восстановлен';
					$sTextToggle='Удалить';
				}
			} else {
				$sMsgTitle='Ошибка!';
				$sMsg='Возникли технические проблемы!';
			}
		} else {
			$sMsgTitle='Ошибка!';
			$sMsg='Комментарий не найден!';
		}		
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Нет доступа!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"     => $bState,
"sTextToggle"     => $sTextToggle,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>