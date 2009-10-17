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
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iValue=getRequest('value',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oTopic=$oEngine->Topic_GetTopicById(getRequest('idTopic',null,'post'))) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oTopic->getUserId()!=$oUserCurrent->getId()) {
			if (!($oTopicVote=$oEngine->Vote_GetVote($oTopic->getId(),'topic',$oUserCurrent->getId()))) {
				if (strtotime($oTopic->getDateAdd())>time()-Config::Get('acl.vote.topic.limit_time')) {
					if ($oEngine->ACL_CanVoteTopic($oUserCurrent,$oTopic) or $iValue==0) {
						if (in_array($iValue,array('1','-1','0'))) {
							$oTopicVote=Engine::GetEntity('Vote');
							$oTopicVote->setTargetId($oTopic->getId());
							$oTopicVote->setTargetType('topic');
							$oTopicVote->setVoterId($oUserCurrent->getId());
							$oTopicVote->setDirection($iValue);
							$oTopicVote->setDate(date("Y-m-d H:i:s"));
							$iVal=0;
							if ($iValue!=0) {
								$iVal=(float)$oEngine->Rating_VoteTopic($oUserCurrent,$oTopic,$iValue);
							}
							$oTopicVote->setValue($iVal);
							$oTopic->setCountVote($oTopic->getCountVote()+1);
							if ($oEngine->Vote_AddVote($oTopicVote) and $oEngine->Topic_UpdateTopic($oTopic)) {
								$bStateError=false;
								$sMsgTitle=$oEngine->Lang_Get('attention');
								$sMsg = $iValue==0 ? $oEngine->Lang_Get('topic_vote_ok_abstain') : $oEngine->Lang_Get('topic_vote_ok');
								$iRating=$oTopic->getRating();
							} else {
								$sMsgTitle=$oEngine->Lang_Get('error');
								$sMsg=$oEngine->Lang_Get('system_error');
							}
						} else {
							$sMsgTitle=$oEngine->Lang_Get('attention');
							$sMsg=$oEngine->Lang_Get('system_error');
						}
					} else {
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('topic_vote_error_acl');
					}
				} else {
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('topic_vote_error_time');
				}
			} else {
				$sMsgTitle=$oEngine->Lang_Get('attention');
				$sMsg=$oEngine->Lang_Get('topic_vote_error_already');
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('attention');
			$sMsg=$oEngine->Lang_Get('topic_vote_error_self');
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
"iRating"   => $iRating,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>