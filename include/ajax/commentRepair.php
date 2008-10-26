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
 * Удаление комментария админом
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$idComment=@$_REQUEST['idComment'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$sCommentText='';
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($oUserCurrent->isAdministrator()) {
		if ($oComment=$oEngine->Comment_GetCommentById($idComment)) {
			$oComment->setDelete(0);
			if ($oEngine->Comment_UpdateTopicComment($oComment)) {
				$sCommentText=$oComment->getText();
				$bStateError=false;
				$sMsgTitle='Отлично!';
				$sMsg='Комментарий восстановлен';
			} else {
				$sMsgTitle='Ошибка!';
				$sMsg='Возникли проблемы при восстановлении!';
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
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"sCommentText" => $sCommentText,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>