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
 * Обрабатывает получение новых комментов в прямом эфире
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';


if ($aComments=$oEngine->Comment_GetCommentsAllGroup(20)) {
	$bStateError=false;
	$oEngine->Viewer_VarAssign();
	$oEngine->Viewer_Assign('aComments',$aComments);
	$sTextResult=$oEngine->Viewer_Fetch("block.stream_comment.tpl");
} else {
	$sMsgTitle='Ошибка!';
	$sMsg='Возникли проблемы, повторите позже.';
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
"sMsgTitle" => $sMsgTitle,
"sMsg" => $sMsg,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>