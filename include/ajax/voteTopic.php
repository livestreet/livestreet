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
 * Голосование за топик
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$iValue=@$_REQUEST['value'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oTopic=$oEngine->Topic_GetTopicById(@$_REQUEST['idTopic'])) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oTopic->getUserId()!=$oUserCurrent->getId()) {
			if (!($oTopicVote=$oEngine->Topic_GetTopicVote($oTopic->getId(),$oUserCurrent->getId()))) {
				if ($oEngine->ACL_CanVoteTopic($oUserCurrent,$oTopic) or $iValue==0) {
					if (in_array($iValue,array('1','-1','0'))) {
						$oTopicVote=new TopicEntity_TopicVote();
						$oTopicVote->setTopicId($oTopic->getId());
						$oTopicVote->setVoterId($oUserCurrent->getId());
						$oTopicVote->setDelta($iValue);
						//$oTopic->setRating($oTopic->getRating()+$iValue);
						if ($iValue!=0) {
							$oEngine->Rating_VoteTopic($oUserCurrent,$oTopic,$iValue);
						}
						$oTopic->setCountVote($oTopic->getCountVote()+1);
						if ($oEngine->Topic_AddTopicVote($oTopicVote) and $oEngine->Topic_UpdateTopic($oTopic)) {
							$bStateError=false;
							$sMsgTitle='Поздравляем!';
							$sMsg = $iValue==0 ? 'Вы воздержались для просмотра рейтинга топика' : 'Ваш голос учтен';
							$iRating=$oTopic->getRating();
						} else {
							$sMsgTitle='Ошибка!';
							$sMsg='Попробуйте проголосовать позже';
						}
					} else {
						$sMsgTitle='Внимание!';
						$sMsg='Голосовать можно только +1, 0, либо -1!';
					}
				} else {
					$sMsgTitle='Внимание!';
					$sMsg='У вас не хватает рейтинга и силы для голосования!';
				}
			} else {
				$sMsgTitle='Внимание!';
				$sMsg='Вы уже голосовали за этот топик!';
			}
		} else {
			$sMsgTitle='Внимание!';
			$sMsg='Вы не можете голосовать за свой топик!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Вы голосуете за несуществующий топик!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для голосования необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"iRating"   => $iRating,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>