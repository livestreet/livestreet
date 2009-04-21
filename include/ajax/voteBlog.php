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

$iValue=@$_REQUEST['value'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iRating=0;
$iCountVote=0;
if ($oEngine->User_IsAuthorization()) {
	if ($oBlog=$oEngine->Blog_GetBlogById(@$_REQUEST['idBlog'])) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		if ($oBlog->getOwnerId()!=$oUserCurrent->getId()) {
			if (!($oBlogVote=$oEngine->Blog_GetBlogVote($oBlog->getId(),$oUserCurrent->getId()))) {
				if ($oEngine->ACL_CanVoteBlog($oUserCurrent,$oBlog)) {
					if (in_array($iValue,array('1','-1'))) {
						$oBlogVote=new BlogEntity_BlogVote();
						$oBlogVote->setBlogId($oBlog->getId());
						$oBlogVote->setVoterId($oUserCurrent->getId());
						$oBlogVote->setDelta($iValue);
						//$oBlog->setRating($oBlog->getRating()+$iValue);
						$oEngine->Rating_VoteBlog($oUserCurrent,$oBlog,$iValue);
						$oBlog->setCountVote($oBlog->getCountVote()+1);
						if ($oEngine->Blog_AddBlogVote($oBlogVote) and $oEngine->Blog_UpdateBlog($oBlog)) {
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
				} else {
					$sMsgTitle=$oEngine->Lang_Get('attention');
					$sMsg=$oEngine->Lang_Get('blog_vote_error_acl');
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