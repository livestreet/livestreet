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
 * Голосование за пользователя
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$iValue=@$_REQUEST['value'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
$iSkill=0;
$iCountVote=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oUser=$oEngine->User_GetUserById(@$_REQUEST['idUser'])) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oUser->getId()!=$oUserCurrent->getId()) {
			if (!($oUserVote=$oEngine->User_GetUserVote($oUser->getId(),$oUserCurrent->getId()))) {
				if ($oEngine->ACL_CanVoteUser($oUserCurrent,$oUser)) {
					if (in_array($iValue,array('1','-1'))) {
						$oUserVote=new UserEntity_UserVote();
						$oUserVote->setUserId($oUser->getId());
						$oUserVote->setVoterId($oUserCurrent->getId());
						$oUserVote->setDelta($iValue);
						//$oUser->setRating($oUser->getRating()+$iValue);
						$oEngine->Rating_VoteUser($oUserCurrent,$oUser,$iValue);
						$oUser->setCountVote($oUser->getCountVote()+1);
						if ($oEngine->User_AddUserVote($oUserVote) and $oEngine->User_Update($oUser)) {
							$bStateError=false;
							$sMsgTitle='Поздравляем!';
							$sMsg='Ваш голос учтен';
							$iRating=$oUser->getRating();
							$iSkill=$oUser->getSkill();
							$iCountVote=$oUser->getCountVote();
						} else {
							$sMsgTitle='Ошибка!';
							$sMsg='Попробуйте проголосовать позже';
						}
					} else {
						$sMsgTitle='Внимание!';
						$sMsg='Голосовать можно только +1 либо -1!';
					}
				} else {
					$sMsgTitle='Внимание!';
					$sMsg='У вас не хватает рейтинга и силы для голосования!';
				}
			} else {
				$sMsgTitle='Внимание!';
				$sMsg='Вы уже голосовали за этого пользователя!';
			}
		} else {
			$sMsgTitle='Внимание!';
			$sMsg='Вы не можете голосовать за себя!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='У нас нет такого пользователя!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для голосоввания необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"iRating"   => $iRating,
"iSkill" => $iSkill,
"iCountVote" => $iCountVote,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>