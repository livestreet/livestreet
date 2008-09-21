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
$idTopic=@$_REQUEST['idTopic'];
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
				$oFavouriteTopicNew=new TopicEntity_FavouriteTopic();
				$oFavouriteTopicNew->setTopicId($oTopic->getId());
				$oFavouriteTopicNew->setUserId($oUserCurrent->getId());
				if ($oEngine->Topic_AddFavouriteTopic($oFavouriteTopicNew)) {
					$bStateError=false;
					$sMsgTitle='Поздравляем!';
					$sMsg='Топик добавлен в избранное';
					$bState=true;
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='Внутреняя ошибка, попробуйте позже';
				}
			}
			if (!$oFavouriteTopic and !$iType) {
				$sMsgTitle='Ошибка!';
				$sMsg='Этого топика нет в вашем избранном';
			}
			if ($oFavouriteTopic and $iType) {
				$sMsgTitle='Ошибка!';
				$sMsg='Этот топик уже есть в вашем избранном';
			}
			if ($oFavouriteTopic and !$iType) {
				if ($oEngine->Topic_DeleteFavouriteTopic($oFavouriteTopic)) {
					$bStateError=false;
					$sMsgTitle='Внимание!';
					$sMsg='Топик удален из избранного';
					$bState=false;
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='Внутреняя ошибка, попробуйте позже';
				}
			}
		} else {
			$sMsgTitle='Ошибка!';
			$sMsg='Топик не найден!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Что вы пытаетесь сделать с этим топиком?!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для добавления/удаления топика в избранное необходимо авторизоваться!';
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