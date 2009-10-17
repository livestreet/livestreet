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
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iValue=getRequest('value',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
$iSkill=0;
$iCountVote=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oUser=$oEngine->User_GetUserById(getRequest('idUser',null,'post'))) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oUser->getId()!=$oUserCurrent->getId()) {
			if (!($oUserVote=$oEngine->Vote_GetVote($oUser->getId(),'user',$oUserCurrent->getId()))) {
				if ($oEngine->ACL_CanVoteUser($oUserCurrent,$oUser)) {
					if (in_array($iValue,array('1','-1'))) {
						$oUserVote=Engine::GetEntity('Vote');
						$oUserVote->setTargetId($oUser->getId());
						$oUserVote->setTargetType('user');
						$oUserVote->setVoterId($oUserCurrent->getId());
						$oUserVote->setDirection($iValue);
						$oUserVote->setDate(date("Y-m-d H:i:s"));
						$iVal=(float)$oEngine->Rating_VoteUser($oUserCurrent,$oUser,$iValue);
						$oUserVote->setValue($iVal);
						//$oUser->setRating($oUser->getRating()+$iValue);
						$oUser->setCountVote($oUser->getCountVote()+1);
						if ($oEngine->Vote_AddVote($oUserVote) and $oEngine->User_Update($oUser)) {
							$bStateError=false;
							$sMsgTitle=$oEngine->Lang_Get('attention');
							$sMsg=$oEngine->Lang_Get('user_vote_ok');
							$iRating=$oUser->getRating();
							$iSkill=$oUser->getSkill();
							$iCountVote=$oUser->getCountVote();
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
					$sMsg=$oEngine->Lang_Get('user_vote_error_acl');
				}
			} else {
				$sMsgTitle=$oEngine->Lang_Get('attention');
				$sMsg=$oEngine->Lang_Get('user_vote_error_already');
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('attention');
			$sMsg=$oEngine->Lang_Get('user_vote_error_self');
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
"iSkill" => $iSkill,
"iCountVote" => $iCountVote,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>