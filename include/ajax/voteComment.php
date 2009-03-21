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
 * Голосование за комментарий
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iValue=@$_REQUEST['value'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oComment=$oEngine->Comment_GetCommentById(@$_REQUEST['idComment'])) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oComment->getUserId()!=$oUserCurrent->getId()) {
			if (!($oTopicCommentVote=$oEngine->Comment_GetTopicCommentVote($oComment->getId(),$oUserCurrent->getId()))) {
				if ($oEngine->ACL_CanVoteComment($oUserCurrent,$oComment)) {
					if (in_array($iValue,array('1','-1'))) {
						$oTopicCommentVote=new CommentEntity_TopicCommentVote();
						$oTopicCommentVote->setCommentId($oComment->getId());
						$oTopicCommentVote->setVoterId($oUserCurrent->getId());
						$oTopicCommentVote->setDelta($iValue);						
						//$oComment->setRating($oComment->getRating()+$iValue);
						$oEngine->Rating_VoteComment($oUserCurrent,$oComment,$iValue);
						
						$oComment->setCountVote($oComment->getCountVote()+1);
						if ($oEngine->Comment_AddTopicCommentVote($oTopicCommentVote) and $oEngine->Comment_UpdateTopicComment($oComment)) {
							$bStateError=false;
							$sMsgTitle='Поздравляем!';
							$sMsg='Ваш голос учтен';
							$iRating=$oComment->getRating();
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
				$sMsg='Вы уже голосовали за этот комментарий!';
			}			
		} else {
			$sMsgTitle='Внимание!';
			$sMsg='Вы не можете голосовать за свой комментарий!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Вы голосуете за несуществующий комментарий!!';
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