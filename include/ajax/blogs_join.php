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
 * Обрабатывает получение TOP подключенных блогов блогов
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';

if ($oEngine->User_IsAuthorization()) {
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if ($aBlogs=$oEngine->Blog_GetBlogsRatingJoin($oUserCurrent->getId(),Config::Get('block.blogs.row'))) {		
		$bStateError=false;
		$oEngine->Viewer_VarAssign();
		$oEngine->Viewer_Assign('aBlogs',$aBlogs);
		$sTextResult=$oEngine->Viewer_Fetch("block.blogs_top.tpl");
	} else {
		$sMsgTitle=$oEngine->Lang_Get('attention');
		$sMsg=$oEngine->Lang_Get('block_blogs_join_error');
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