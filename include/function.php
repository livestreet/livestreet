<?
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
 * Если не стоит расширения mb
 *
 * @param unknown_type $s
 * @return unknown
 */
if (!function_exists('mb_strlen')) {
	function mb_strlen($s,$sEncode="UTF-8") {		
		$length = strlen(iconv($sEncode, 'Windows-1251', $s));
      	return (int)$length;
	}
}

/**
 * Если не стоит расширения mb
 */
if (!function_exists('mb_strtolower')) {
	function mb_strtolower($s,$sEncode="UTF-8") {		
		$s=iconv($sEncode,"Windows-1251",$s);
		$s=strtolower($s);
		$s=iconv("Windows-1251",$sEncode,$s);
		return $s;
	}
}

/**
 * функция вывода отладочных сообщений через "хакерскую" консоль Дмитрия Котерова
 */
if (SYS_HACKER_CONSOLE) {	
	require_once "classes/lib/external/HackerConsole/Main.php";
	new Debug_HackerConsole_Main(true);
}

function dump($msg) {	
	if (SYS_HACKER_CONSOLE) {
		call_user_func(array('Debug_HackerConsole_Main', 'out'), $msg);
	} else {
		//var_dump($msg);
	}
}



/**
 * функция доступа к GET POST параметрам 
 */
function getRequest($sName,$default=null) {
	if (isset($_REQUEST[$sName])) {
		if (is_string($_REQUEST[$sName])) {
			return trim($_REQUEST[$sName]);
		} else {
			return $_REQUEST[$sName];
		}
	}
	return $default;
}

/**
 * генерирует случайную последовательность символов
 *
 * @param unknown_type $iLength
 * @return unknown
 */
function func_generator($iLength=10) {
	if ($iLength>32) {
		$iLength=32;
	}
	return substr(md5(uniqid(rand(),true)),0,$iLength);
}

/**
 * htmlspecialchars умеющая обрабатывать массивы
 *
 * @param unknown_type $data
 */
function func_htmlspecialchars(&$data) {
	if (is_array($data)) {
		foreach ($data as $sKey => $value) {
			if (is_array($value)) {
				func_htmlspecialchars($data[$sKey]);
			} else {
				$data[$sKey]=htmlspecialchars($value);
			}
		}
	} else {
		$data=htmlspecialchars($data);
	}
}

/**
 * stripslashes умеющая обрабатывать массивы
 *
 * @param unknown_type $data
 */
function func_stripslashes(&$data) {
	if (is_array($data)) {
		foreach ($data as $sKey => $value) {
			if (is_array($value)) {
				func_stripslashes($data[$sKey]);
			} else {
				$data[$sKey]=stripslashes($value);
			}
		}
	} else {
		$data=stripslashes($data);
	}
}

/**
 * Проверяет на корректность значение соглавно правилу
 *
 * @param unknown_type $sValue
 * @param unknown_type $sParam
 * @param unknown_type $iMin
 * @param unknown_type $iMax
 * @return unknown
 */
function func_check($sValue,$sParam,$iMin=1,$iMax=100) {
	switch($sParam)
	{
		case 'id': if (preg_match("/^\d{".$iMin.','.$iMax."}$/",$sValue)){ return true; } break;				
		case 'float': if (preg_match("/^[\-]?\d+[\.]?\d*$/",$sValue)){ return true; } break;	
		case 'mail': if (preg_match("/^[\da-z\_\-\.]+@[\da-z_\-\.]+\.[a-z]{2,5}$/i",$sValue)){ return true; } break;
		case 'login': if (preg_match("/^[\da-z\_\-]{".$iMin.','.$iMax."}$/i",$sValue)){ return true; } break;
		case 'md5': if (preg_match("/^[\da-z]{32}$/i",$sValue)){ return true; } break;
		case 'password': if (mb_strlen($sValue,'UTF-8')>=$iMin){ return true; } break;
		case 'text': if (mb_strlen($sValue,'UTF-8')>=$iMin and mb_strlen($sValue,'UTF-8')<=$iMax){ return true; } break;
		default: 
			return false;
	}
	return false;
}

/**
 * Шифрование
 *
 * @param unknown_type $sData
 * @return unknown
 */
function func_encrypt($sData) {
	return md5($sData);
}



/**
 * Определяет IP адрес
 *
 * @return unknown
 */
function func_getIp() {
    return $_SERVER['REMOTE_ADDR'];
} 


/**
 * Заменяет стандартную header('Location: *');
 *
 * @param unknown_type $sLocation
 */
function func_header_location($sLocation) {   	
    header('Location: '.$sLocation);
    exit();
}

/**
 * Рейсайзинг картинок
 *
 * @param unknown_type $sFileSrc
 * @param unknown_type $sDirDest
 * @param unknown_type $sFileDest
 * @param unknown_type $iWidthMax
 * @param unknown_type $iHeightMax
 * @param unknown_type $iWidthDest
 * @param unknown_type $iHeightDest
 * @param unknown_type $bForcedMinSize
 * @return unknown
 */
function func_img_resize($sFileSrc,$sDirDest,$sFileDest,$iWidthMax,$iHeightMax,$iWidthDest=null,$iHeightDest=null,$bForcedMinSize=true) {
	if (!($aSize=getimagesize($sFileSrc))) {		
		return false;
	}	
	$img_src=false;
	switch ($aSize['mime']) {
		case 'image/png':
			$img_src=imagecreatefrompng($sFileSrc);
			$sFileDest.='.png';
			break;
		case 'image/gif':
			$img_src=imagecreatefromgif($sFileSrc);
			$sFileDest.='.gif';
			break;
		case 'image/jpeg':
			$img_src=imagecreatefromjpeg($sFileSrc);
			$sFileDest.='.jpg';
			break;
		default:
			return false;
			break;
	}
	if (!$img_src) {		
		return false;
	}
	if (($aSize[0]>$iWidthMax) or ($aSize[1]>$iHeightMax)) {
		return false;
	}
	if ($iWidthDest) {
		if (!$bForcedMinSize and ($iWidthDest>$aSize[0])) {
			$iWidthDest=$aSize[0];
		}
		$iWidthNew=$iWidthDest;
		if ($iHeightDest) {			
			$iHeightNew=$iHeightDest;
		} else {
			$iSizeRelation=$iWidthDest/$aSize[0];			
			$iHeightNew=round($iSizeRelation*$aSize[1]);
		}
	} else {
		$iWidthNew=$aSize[0];
		$iHeightNew=$aSize[1];
	}
	
	$sFileFullPath=DIR_SERVER_ROOT.'/'.$sDirDest.'/'.$sFileDest;
	@func_mkdir(DIR_SERVER_ROOT,$sDirDest);
	if ($iWidthDest and $iWidthDest!=$aSize[0]) {
		$img_dest=imagecreatetruecolor($iWidthNew,$iHeightNew);		
		imagesavealpha($img_dest,true);
		imagealphablending($img_dest,false);
		if (imagecopyresampled($img_dest,$img_src,0,0,0,0,$iWidthNew,$iHeightNew,$aSize[0],$aSize[1])) {				
			imagedestroy($img_src);
			switch ($aSize['mime']) {
				case 'image/png':
					if (imagepng($img_dest,$sFileFullPath)) {
						chmod($sFileFullPath,0666);
						return $sFileDest;
					}
					break;
				case 'image/gif':
					if (imagegif($img_dest,$sFileFullPath)) {
						chmod($sFileFullPath,0666);
						return $sFileDest;
					}
					break;
				case 'image/jpeg':
					if (imagejpeg($img_dest,$sFileFullPath)) {
						chmod($sFileFullPath,0666);
						return $sFileDest;
					}
					break;
			}
		}
	} else {
		if (copy($sFileSrc,$sFileFullPath)) {
			return $sFileDest;
		}
	}
	return false;
}

/**
 * Создаёт каталог по полному пути
 *
 * @param unknown_type $sBasePath
 * @param unknown_type $sNewDir
 */
function func_mkdir($sBasePath,$sNewDir) {
	$sBasePath=rtrim($sBasePath,'/');
	$sBasePath.='/';
	$sTempPath=$sBasePath;
	$aNewDir=explode('/',$sNewDir);
	foreach ($aNewDir as $sDir) {
		if ($sDir!='.' and $sDir!='') {
			if (!file_exists($sTempPath.$sDir.'/'))	{
				@mkdir($sTempPath.$sDir.'/');
				@chmod($sTempPath.$sDir.'/',0755);
			}
			$sTempPath=$sTempPath.$sDir.'/';
		}
	}   	
}

/**
 * Форматирует дату
 *
 * @param unknown_type $sDate
 * @param unknown_type $sFormat
 * @return unknown
 */
function func_date($sDate,$sFormat="j rus_mon Y, H:i") {
	$aMonth=array(
		'января',
		'февраля',
		'марта',
		'апреля',
		'мая',
		'июня',
		'июля',
		'августа',
		'сентября',
		'октября',
		'ноября',
		'декабря'
	);
	if (preg_match("/^\d+$/",$sDate)) {
		$iDate=$sDate;
	} else {
		$iDate=strtotime($sDate);
	}
	
	$iMonth=date("m",$iDate);	
	$sMonth=$aMonth[$iMonth-1];
	$sFormat=str_replace("rus_mon",$sMonth,$sFormat);
	
		
	$sDate=date($sFormat,$iDate);
	
	return $sDate;
}

/**
 * Функция форматирование даты для плагина Smarty
 *
 * @param unknown_type $aParams
 * @return unknown
 */
function func_date_smarty($aParams)
{
	if (empty($aParams['date'])) {
		$sDate=time();
	} else {
		$sDate=$aParams['date'];
	}	
	if(empty($aParams['format'])) {
		return func_date($sDate);
	} else {
		return func_date($sDate,$aParams['format']);
	}
}

/**
 * Возвращает обрезанный текст по заданное число слов
 *
 * @param unknown_type $sText
 * @param unknown_type $iCountWords
 */
function func_text_words($sText,$iCountWords) {
	$sText=str_replace("\r\n",'[<rn>]',$sText);
	$sText=str_replace("\n",'[<n>]',$sText);
		
	$iCount=0;
	$aWordsResult=array();
	$aWords=preg_split("/\s+/",$sText);	
	for($i=0;$i<count($aWords);$i++) {
		if ($iCount>=$iCountWords) {
			break;
		}
		if ($aWords[$i]!='[<rn>]' and $aWords[$i]!='[<n>]') {
			$aWordsResult[]=$aWords[$i];
			$iCount++;
		}
	}
	$sText=join(' ',$aWordsResult);	
	$sText=str_replace('[<rn>]'," ",$sText);
	$sText=str_replace('[<n>]'," ",$sText);	
	return $sText;	
}

?>