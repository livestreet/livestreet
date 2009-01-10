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
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$idCommentLast=@$_REQUEST['idCommentLast'];
$idTopic=@$_REQUEST['idTopic'];
$bStateError=true;
$sMsg='';
$sMsgTitle='';
$iMaxIdComment=0;
$aComments=array();
if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	$aReturn=$oEngine->Comment_GetCommentsNewByTopicId($idTopic,$idCommentLast);
	$iMaxIdComment=$aReturn['iMaxIdComment'];
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
	$sMsgTitle='Ошибка!';
	$sMsg='Необходимо авторизоваться!';
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"aComments" => $aComments,
"iMaxIdComment" => $iMaxIdComment,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>