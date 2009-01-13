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
 * Обрабатывает текс для предпросмотра контента
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
chdir(dirname(dirname(dirname(__FILE__))));
require_once("./config/config.ajax.php");

$sText=@$_REQUEST['text'];
$bSave=@$_REQUEST['save'];
$bStateError=true;
$sTextResult='';
if ($oEngine->User_IsAuthorization()) {
	if ($bSave) {
		$sTextResult=htmlspecialchars($sText);
	} else {
		//var_dump(htmlspecialchars($sText));
		$sTextResult=$oEngine->Text_Parser($sText);
		//var_dump(htmlspecialchars($sTextResult));
	}
	$bStateError=false;
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sTextResult,
);

?>
<pre>
<b>Request method:</b> <?=$_SERVER['REQUEST_METHOD'] . "\n"?>
<b>Loader used:</b> <?=$JsHttpRequest->LOADER . "\n"?>
<b>_REQUEST:</b> <?=print_r($_REQUEST, 1)?>
</pre>