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
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$iType=@$_REQUEST['type'];
$idUser=@$_REQUEST['idUser'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$bState=false;
if ($oEngine->User_IsAuthorization()) {
	if (in_array($iType,array('1','0'))) {
		if ($oUser=$oEngine->User_GetUserById($idUser)) {
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			$oFrend=$oEngine->User_GetFrend($oUser->getId(),$oUserCurrent->getId());
			if (!$oFrend and $iType) {
				$oFrendNew=new UserEntity_Frend();
				$oFrendNew->setFrendId($oUser->getId());
				$oFrendNew->setUserId($oUserCurrent->getId());
				if ($oEngine->User_AddFrend($oFrendNew)) {
					$bStateError=false;
					$sMsgTitle='Поздравляем!';
					$sMsg='У вас появился новый друг';
					$bState=true;
					$oEngine->Notify_SendUserFriendNew($oUser,$oUserCurrent);
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='Внутреняя ошибка, попробуйте позже';
				}
			}
			if (!$oFrend and !$iType) {
				$sMsgTitle='Ошибка!';
				$sMsg='У вас нет такого друга';
			}
			if ($oFrend and $iType) {
				$sMsgTitle='Ошибка!';
				$sMsg='Он уже и так ваш друг';
			}
			if ($oFrend and !$iType) {
				if ($oEngine->User_DeleteFrend($oFrend)) {
					$bStateError=false;
					$sMsgTitle='Внимание!';
					$sMsg='У вас больше нет этого друга';
					$bState=false;
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
		$sMsg='Что вы пытаетесь сделать с этим пользователем?!';
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
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>