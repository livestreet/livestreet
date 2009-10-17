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
 * Выводит информацию о блоге(description)
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$sBlogId=getRequest('idBlog',null,'post');
$bStateError=true;
$sText='';
$oBlog=null;
if ($sBlogId==0) {
	if ($oEngine->User_IsAuthorization()) {
		$oUserCurrent=$oEngine->User_GetUserCurrent();
		$oBlog=$oEngine->Blog_GetPersonalBlogByUserId($oUserCurrent->getId());
	}	
} else {
	$oBlog=$oEngine->Blog_GetBlogById($sBlogId);
}

if ($oBlog) {
	$bStateError=false;
	$sText=$oBlog->getDescription();
} 


$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sText"   => $sText,
);

?>