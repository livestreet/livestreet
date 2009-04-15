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
 * Обрабатывает голосование за топик-вопрос
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idAnswer=@$_REQUEST['idAnswer'];
$idTopic=@$_REQUEST['idTopic'];
$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';
if ($oEngine->User_IsAuthorization()) {
	if ($oTopic=$oEngine->Topic_GetTopicById($idTopic)) {
		if ($oTopic->getType()=='question') {
			//голосовал уже или нет
			$oUserCurrent=$oEngine->User_GetUserCurrent();
			if (!($oTopicQuestionVote=$oEngine->Topic_GetTopicQuestionVote($oTopic->getId(),$oUserCurrent->getId()))) {
				$aAnswer=$oTopic->getQuestionAnswers();							
				if (isset($aAnswer[$idAnswer]) or $idAnswer==-1) {						
					if ($idAnswer==-1) {
						$oTopic->setQuestionCountVoteAbstain($oTopic->getQuestionCountVoteAbstain()+1);
					} else {
						$oTopic->increaseQuestionAnswerVote($idAnswer);
					}
					$oTopic->setQuestionCountVote($oTopic->getQuestionCountVote()+1);
					
					$oTopicQuestionVote=new TopicEntity_TopicQuestionVote();
					$oTopicQuestionVote->setTopicId($oTopic->getId());
					$oTopicQuestionVote->setVoterId($oUserCurrent->getId());
					$oTopicQuestionVote->setAnswer($idAnswer);	
					
					if ($oEngine->Topic_AddTopicQuestionVote($oTopicQuestionVote) and $oEngine->Topic_updateTopic($oTopic)) {
						$bStateError=false;
						$sMsgTitle='Поздравляем!';
						$sMsg='Ваш голос учтен.';						
						
						$oEngine->Viewer_Assign('oTopic',$oTopic);								
						$sTextResult=$oEngine->Viewer_Fetch("topic_question.tpl");	
					} else {
						$sMsgTitle='Ошибка!';
						$sMsg='Возникли проблемы, повторите позже.';
					}
				} else {
					$sMsgTitle='Ошибка!';
					$sMsg='За что вы голосуете?!';
				}
			} else {
				$sMsgTitle='Ошибка!';
				$sMsg='Ваш голос уже учтен!';
			}
		} else {
			$sMsgTitle='Ошибка!';
			$sMsg='В этом топике нельзя голосовать!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Как можно голосовать на несуществующий опрос?';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для голосования необходимо авторизоваться!';
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
"sMsgTitle" => $sMsgTitle,
"sMsg" => $sMsg,
);

?>