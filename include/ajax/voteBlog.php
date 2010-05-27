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
 * Голосование за блог
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$iValue=getRequest('value',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
$iCountVote=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oBlog=$oEngine->Blog_GetBlogById(getRequest('idBlog',null,'post'))) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oBlog->getOwnerId()!=$oUserCurrent->getId()) {
			if (!($oBlogVote=$oEngine->Vote_GetVote($oBlog->getId(),'blog',$oUserCurrent->getId()))) {
				switch($oEngine->ACL_CanVoteBlog($oUserCurrent,$oBlog)) {
					case ModuleACL::CAN_VOTE_BLOG_TRUE:
						if (in_array($iValue,array('1','-1'))) {
							$oBlogVote=Engine::GetEntity('Vote');
							$oBlogVote->setTargetId($oBlog->getId());
							$oBlogVote->setTargetType('blog');
							$oBlogVote->setVoterId($oUserCurrent->getId());
							$oBlogVote->setDirection($iValue);
							$oBlogVote->setDate(date("Y-m-d H:i:s"));
							$iVal=(float)$oEngine->Rating_VoteBlog($oUserCurrent,$oBlog,$iValue);
							$oBlogVote->setValue($iVal);
							$oBlog->setCountVote($oBlog->getCountVote()+1);
							if ($oEngine->Vote_AddVote($oBlogVote) and $oEngine->Blog_UpdateBlog($oBlog)) {
								$bStateError=false;
								$sMsgTitle=$oEngine->Lang_Get('attention');
								$sMsg=$oEngine->Lang_Get('blog_vote_ok');
								$iRating=$oBlog->getRating();
								$iCountVote=$oBlog->getCountVote();
							} else {
								$sMsgTitle=$oEngine->Lang_Get('error');
								$sMsg=$oEngine->Lang_Get('system_error');
							}
						} else {
							$sMsgTitle=$oEngine->Lang_Get('attention');
							$sMsg=$oEngine->Lang_Get('system_error');
						}
						break;
					case ModuleACL::CAN_VOTE_BLOG_ERROR_CLOSE:
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('blog_vote_error_close');						
						break;
						
					default:
					case ModuleACL::CAN_VOTE_BLOG_FALSE:
						$sMsgTitle=$oEngine->Lang_Get('attention');
						$sMsg=$oEngine->Lang_Get('blog_vote_error_acl');					
						break;
				}
			} else {
				$sMsgTitle=$oEngine->Lang_Get('attention');
				$sMsg=$oEngine->Lang_Get('blog_vote_error_already');
			}
		} else {
			$sMsgTitle=$oEngine->Lang_Get('attention');
			$sMsg=$oEngine->Lang_Get('blog_vote_error_self');
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
"iCountVote" => $iCountVote,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>