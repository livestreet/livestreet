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
 * Получение новых комментов
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$idCommentLast=getRequest('idCommentLast',null,'post');
$selfIdComment=getRequest('selfIdComment',null,'post');
$idTopic=getRequest('idTarget',null,'post');
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iMaxIdComment=0;
$aComments=array();
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($oTopic=$oEngine->Topic_GetTopicById($idTopic)) {
		
		if (getRequest('bUsePaging',null,'post') and $selfIdComment) {			
			if ($oComment=$oEngine->Comment_GetCommentById($selfIdComment) and $oComment->getTargetId()==$oTopic->getId() and $oComment->getTargetType()=='topic') {
				$oViewerLocal=$oEngine->Viewer_GetLocalViewer();
				$oViewerLocal->Assign('oUserCurrent',$oEngine->User_GetUserCurrent());
				$oViewerLocal->Assign('bOneComment',true);

				$oViewerLocal->Assign('oComment',$oComment);
				$sText=$oViewerLocal->Fetch("comment.tpl");
				$aCmt=array();
				$aCmt[]=array(
					'html' => $sText,
					'obj'  => $oComment,
				);
			} else {
				$aCmt=array();
			}
			$aReturn['comments']=$aCmt;
			$aReturn['iMaxIdComment']=$selfIdComment;
		} else {
			$aReturn=$oEngine->Comment_GetCommentsNewByTargetId($oTopic->getId(),'topic',$idCommentLast);			
		}
		$iMaxIdComment=$aReturn['iMaxIdComment'];
		
		$oTopicRead=Engine::GetEntity('Topic_TopicRead');
		$oTopicRead->setTopicId($oTopic->getId());
		$oTopicRead->setUserId($oUserCurrent->getId());
		$oTopicRead->setCommentCountLast($oTopic->getCountComment());
		$oTopicRead->setCommentIdLast($iMaxIdComment);
		$oTopicRead->setDateRead(date("Y-m-d H:i:s"));
		$oEngine->Topic_SetTopicRead($oTopicRead);
		
		$aCmts=$aReturn['comments'];
		if ($aCmts and is_array($aCmts)) {
			foreach ($aCmts as $aCmt) {
				$aComments[]=array(
					'html' => $aCmt['html'],
					'idParent' => $aCmt['obj']->getPid(),
					'id' => $aCmt['obj']->getId(),
				);
			}
		}
		$bStateError=false;
	} else {
		$sMsgTitle=$oEngine->Lang_Get('error');
		$sMsg=$oEngine->Lang_Get('system_error');
	}
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$aResult = array(
"bStateError"     => $bStateError,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"aComments" => $aComments,
"iMaxIdComment" => $iMaxIdComment,
);

if (!headers_sent()) {
	header('Content-type: application/json');
}
echo json_encode($aResult);
?>