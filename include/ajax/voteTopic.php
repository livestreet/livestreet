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
				if (strtotime($oTopic->getDateAdd())>time()-VOTE_LIMIT_TIME_TOPIC) {
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