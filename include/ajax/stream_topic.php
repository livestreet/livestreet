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
 * Обрабатывает получение новых отпиков в прямом эфире
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$bStateError=true;
$sTextResult='';
$sMsgTitle='';
$sMsg='';


if ($oTopics=$oEngine->Topic_GetTopicsLast(Config::Get('block.stream.row'))) {
	$bStateError=false;
	$oEngine->Viewer_VarAssign();
	$oEngine->Viewer_Assign('oTopics',$oTopics);
	$sTextResult=$oEngine->Viewer_Fetch("block.stream_topic.tpl");
} else {
	$sMsgTitle=$oEngine->Lang_Get('attention');
	$sMsg=$oEngine->Lang_Get('block_stream_topics_no');
}


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
"sMsgTitle" => $sMsgTitle,
"sMsg" => $sMsg,
);

?>