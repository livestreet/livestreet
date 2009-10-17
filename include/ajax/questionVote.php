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

$idAnswer=getRequest('idAnswer',null,'post');
$idTopic=getRequest('idTopic',null,'post');
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
					
					$oTopicQuestionVote=Engine::GetEntity('Topic_TopicQuestionVote');
					$oTopicQuestionVote->setTopicId($oTopic->getId());
					$oTopicQuestionVote->setVoterId($oUserCurrent->getId());
					$oTopicQuestionVote->setAnswer($idAnswer);	
					
					if ($oEngine->Topic_AddTopicQuestionVote($oTopicQuestionVote) and $oEngine->Topic_updateTopic($oTopic)) {
						$bStateError=false;
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('topic_question_vote_ok');						
						
						$oEngine->Viewer_Assign('oTopic',$oTopic);								
						$sTextResult=$oEngine->Viewer_Fetch("topic_question.tpl");	
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
				$sMsg=$oEngine->Lang_Get('topic_question_vote_already');
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
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
"sMsgTitle" => $sMsgTitle,
"sMsg" => $sMsg,
);

?>