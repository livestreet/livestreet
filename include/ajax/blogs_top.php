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
 * Обрабатывает получение TOP блогов
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';


if ($aResult=$oEngine->Blog_GetBlogsRating(1,Config::Get('block.blogs.row'))) {
	$aBlogs=$aResult['collection'];
	$bStateError=false;
	$oEngine->Viewer_VarAssign();
	$oEngine->Viewer_Assign('aBlogs',$aBlogs);
	$sTextResult=$oEngine->Viewer_Fetch("block.blogs_top.tpl");
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('system_error');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
"sMsgTitle" => $sMsgTitle,
"sMsg" => $sMsg,
);

?>