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

$iValue=getRequest('value',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oComment=$oEngine->Comment_GetCommentById(getRequest('idComment',null,'post'))) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oComment->getUserId()!=$oUserCurrent->getId()) {
			if (!($oTopicCommentVote=$oEngine->Vote_GetVote($oComment->getId(),'comment',$oUserCurrent->getId()))) {
				if (strtotime($oComment->getDate())>time()-Config::Get('acl.vote.comment.limit_time')) {
					if ($oEngine->ACL_CanVoteComment($oUserCurrent,$oComment)) {
						if (in_array($iValue,array('1','-1'))) {							
							$oTopicCommentVote=Engine::GetEntity('Vote');
							$oTopicCommentVote->setTargetId($oComment->getId());
							$oTopicCommentVote->setTargetType('comment');
							$oTopicCommentVote->setVoterId($oUserCurrent->getId());
							$oTopicCommentVote->setDirection($iValue);
							$oTopicCommentVote->setDate(date("Y-m-d H:i:s"));							
							$iVal=(float)$oEngine->Rating_VoteComment($oUserCurrent,$oComment,$iValue);							
							$oTopicCommentVote->setValue($iVal);
							
							$oComment->setCountVote($oComment->getCountVote()+1);
							if ($oEngine->Vote_AddVote($oTopicCommentVote) and $oEngine->Comment_UpdateComment($oComment)) {
								$bStateError=false;
								$sMsgTitle=$oEngine->Lang_Get('attention');
								$sMsg=$oEngine->Lang_Get('comment_vote_ok');
								$iRating=$oComment->getRating();
							} else {
								$sMsgTitle=$oEngine->Lang_Get('error');
								$sMsg=$oEngine->Lang_Get('comment_vote_error');
							}
						} else {
							$sMsgTitle=$oEngine->Lang_Get('attention');
							$sMsg=$oEngine->Lang_Get('comment_vote_error_value');
						}
					} else {
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('comment_vote_error_acl');
					}
				} else {
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('comment_vote_error_time');
				}
			} else {
				$sMsgTitle=$oEngine->Lang_Get('attention');
				$sMsg=$oEngine->Lang_Get('comment_vote_error_already');
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('attention');
			$sMsg=$oEngine->Lang_Get('comment_vote_error_self');
		}
	} else {
		$sMsgTitle=$oEngine->Lang_Get('error');
		$sMsg=$oEngine->Lang_Get('comment_vote_error_noexists');
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