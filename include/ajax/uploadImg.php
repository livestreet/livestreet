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
 * Загрузка картинок
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$aForm=getRequest('value',null,'post');
$bStateError=true;
$sText='';
$sMsg=$oEngine->Lang_Get('system_error');
$sMsgTitle=$oEngine->Lang_Get('error');
if ($oEngine->User_IsAuthorization()) {
	$sFile=null;
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	if (is_uploaded_file($_FILES['img_file']['tmp_name'])) {
		if(!$sFile=$oEngine->Topic_UploadTopicImageFile($_FILES['img_file'],$oUserCurrent)) {
			$sMsgTitle=$oEngine->Lang_Get('error');
			$sMsg=$oEngine->Lang_Get('uploadimg_file_error');
		}
	}

	if (isPost('img_url') && $_REQUEST['img_url']!='' && $_REQUEST['img_url']!='http://') {
		$sFile=$oEngine->Topic_UploadTopicImageUrl($_REQUEST['img_url'],$oUserCurrent);
		switch (true) {
			case is_string($sFile):
			
				break;
			
			case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_READ):
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('uploadimg_url_error_read');
				$sFile=null;
				break;
				
			case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_SIZE):
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('uploadimg_url_error_size');
				$sFile=null;
				break;
				
			case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_TYPE):
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('uploadimg_url_error_type');
				$sFile=null;
				break;
				
			default:
			case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR):
				$sMsgTitle=$oEngine->Lang_Get('error');
				$sMsg=$oEngine->Lang_Get('uploadimg_url_error');
				$sFile=null;
				break;
		}
	}
	
	if ($sFile) {
		$bStateError=false;
		$sMsgTitle='';
		$sMsg='';
		$sText=$oEngine->Image_BuildHTML($sFile, $_REQUEST);
	}	
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$GLOBALS['_RESULT'] = array(
	"bStateError" => $bStateError,
	"sText"       => $sText,
	"sMsgTitle"   => $sMsgTitle,
	"sMsg"        => $sMsg,
);

?>