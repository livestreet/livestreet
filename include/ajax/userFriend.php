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
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$idUser=@$_REQUEST['idUser'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$sToggleText='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($oUserCurrent->getId()!=$idUser) {
		if ($oUser=$oEngine->User_GetUserById($idUser)) {			
			$oFrend=$oEngine->User_GetFrend($oUser->getId(),$oUserCurrent->getId());
			if (!$oFrend) {
				$oFrendNew=new UserEntity_Frend();
				$oFrendNew->setFrendId($oUser->getId());
				$oFrendNew->setUserId($oUserCurrent->getId());
				if ($oEngine->User_AddFrend($oFrendNew)) {
					$bStateError=false;
					$sMsgTitle='Поздравляем!';
					$sMsg='У вас появился новый друг';
					$bState=true;
					$sToggleText=$oEngine->Lang_Get('user_friend_del');
					$oEngine->Notify_SendUserFriendNew($oUser,$oUserCurrent);
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='Внутреняя ошибка, попробуйте позже';
				}
			}			
			if ($oFrend) {
				if ($oEngine->User_DeleteFrend($oFrend)) {
					$bStateError=false;
					$sMsgTitle='Внимание!';
					$sMsg='У вас больше нет этого друга';
					$bState=false;
					$sToggleText=$oEngine->Lang_Get('user_friend_add');
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='Внутреняя ошибка, попробуйте позже';
				}
			}
		} else {
			$sMsgTitle='Ошибка!';
			$sMsg='Друг не найден!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Ваш друг - это вы!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для добавления/удаления друзей необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"bState"   => $bState,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"sToggleText"   => $sToggleText,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>