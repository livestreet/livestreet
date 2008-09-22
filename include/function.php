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
function func_getIp()
{
    global $REMOTE_ADDR;
    global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
    global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
    global $HTTP_SERVER_VARS, $HTTP_ENV_VARS;

        // Get some server/environment variables values
    if (empty($REMOTE_ADDR)) {
        if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        } else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $HTTP_SERVER_VARS['REMOTE_ADDR'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $HTTP_ENV_VARS['REMOTE_ADDR'];
        } else if (@getenv('REMOTE_ADDR')) {
                $REMOTE_ADDR = getenv('REMOTE_ADDR');
        }
    } // end if

    if (empty($HTTP_X_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_X_FORWARDED_FOR'];
        } else if (@getenv('HTTP_X_FORWARDED_FOR')) {
            $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
        }
    } // end if

    if (empty($HTTP_X_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $HTTP_SERVER_VARS['HTTP_X_FORWARDED'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $HTTP_ENV_VARS['HTTP_X_FORWARDED'];
        } else if (@getenv('HTTP_X_FORWARDED')) {
            $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
        }
    } // end if

    if (empty($HTTP_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $HTTP_SERVER_VARS['HTTP_FORWARDED_FOR'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $HTTP_ENV_VARS['HTTP_FORWARDED_FOR'];
        } else if (@getenv('HTTP_FORWARDED_FOR')) {
            $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
        }
    } // end if

    if (empty($HTTP_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $HTTP_SERVER_VARS['HTTP_FORWARDED'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $HTTP_ENV_VARS['HTTP_FORWARDED'];
        } else if (@getenv('HTTP_FORWARDED')) {
            $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
        }
    } // end if

    if (empty($HTTP_VIA)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
            $HTTP_VIA = $_SERVER['HTTP_VIA'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
            $HTTP_VIA = $_ENV['HTTP_VIA'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_VIA'])) {
            $HTTP_VIA = $HTTP_SERVER_VARS['HTTP_VIA'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_VIA'])) {
            $HTTP_VIA = $HTTP_ENV_VARS['HTTP_VIA'];
        } else if (@getenv('HTTP_VIA')) {
            $HTTP_VIA = getenv('HTTP_VIA');
        }
    } // end if

    if (empty($HTTP_X_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
        } else if (!empty($HTTP_SERVER_VARS) && isset($HTTP_SERVER_VARS['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $HTTP_SERVER_VARS['HTTP_X_COMING_FROM'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $HTTP_ENV_VARS['HTTP_X_COMING_FROM'];
        } else if (@getenv('HTTP_X_COMING_FROM')) {
            $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
        }
    } // end if

    if (empty($HTTP_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
        } else if (!empty($HTTP_COMING_FROM) && isset($HTTP_SERVER_VARS['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $HTTP_SERVER_VARS['HTTP_COMING_FROM'];
        } else if (!empty($HTTP_ENV_VARS) && isset($HTTP_ENV_VARS['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $HTTP_ENV_VARS['HTTP_COMING_FROM'];
        } else if (@getenv('HTTP_COMING_FROM')) {
            $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
        }
    } // end if

        // Gets the default ip sent by the user
    if (!empty($REMOTE_ADDR)) {
        $direct_ip = $REMOTE_ADDR;
    }

        // Gets the proxy ip sent by the user
    $proxy_ip = '';
    if (!empty($HTTP_X_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_X_FORWARDED_FOR;
    } else if (!empty($HTTP_X_FORWARDED)) {
        $proxy_ip = $HTTP_X_FORWARDED;
    } else if (!empty($HTTP_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_FORWARDED_FOR;
    } else if (!empty($HTTP_FORWARDED)) {
        $proxy_ip = $HTTP_FORWARDED;
    } else if (!empty($HTTP_VIA)) {
        $proxy_ip = $HTTP_VIA;
    } else if (!empty($HTTP_X_COMING_FROM)) {
        $proxy_ip = $HTTP_X_COMING_FROM;
    } else if (!empty($HTTP_COMING_FROM)) {
        $proxy_ip = $HTTP_COMING_FROM;
    } // end if... else if...

        // Returns the true IP if it has been found, else FALSE
    if (empty($proxy_ip)) {
            // True IP without proxy
        return $direct_ip;
    } else {
        $is_ip = ereg('^([0-9]{1,3}\.){3,3}[0-9]{1,3}', $proxy_ip, $regs);
        if ($is_ip && (count($regs) > 0)) {
                // True IP behind a proxy
            return $regs[0];
        } else {
                // Can't define IP: there is a proxy but we don't have
                // information about the true IP
            return FALSE;
        }
    } // end if... else...
} // end of the 'PMA_getIp()' function


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
    	$sTempPath=$sBasePath;
    	$aNewDir=explode('/',$sNewDir);
    	foreach ($aNewDir as $sDir) {
    		if ($sDir!='.') {    			
    			@mkdir($sTempPath.$sDir.'/');
    			@chmod($sTempPath.$sDir.'/',0777);
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

?>