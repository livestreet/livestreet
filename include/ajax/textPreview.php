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
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

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