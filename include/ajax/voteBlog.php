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
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

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
							$sMsgTitle='Поздравляем!';
							$sMsg='Ваш голос учтен';
							$iRating=$oBlog->getRating();
							$iCountVote=$oBlog->getCountVote();
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
				$sMsg='Вы уже голосовали за этот блог!';
			}
		} else {
			$sMsgTitle='Внимание!';
			$sMsg='Вы не можете голосовать за свой блог!';
		}
	} else {
		$sMsgTitle='Ошибка!';
		$sMsg='Вы голосуете за несуществующий блог!';
	}
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Для голосования необходимо авторизоваться!';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"iRating"   => $iRating,
"iCountVote" => $iCountVote,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>