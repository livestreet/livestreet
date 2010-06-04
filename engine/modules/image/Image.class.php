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

require_once Config::Get('path.root.engine').'/lib/external/LiveImage/Image.php';

/**
 * Модуль обработки изображений
 * Использует библиотеку LiveImage
 *
 */
class ModuleImage extends Module {
	/**
	 * Неопределенный тип ошибки при загрузке изображения
	 */
	const UPLOAD_IMAGE_ERROR      = 1;
	/**
	 * Ошибка избыточного размера при загрузке изображения
	 */
	const UPLOAD_IMAGE_ERROR_SIZE = 2;
	/**
	 * Неизвестный тип изображения
	 */
	const UPLOAD_IMAGE_ERROR_TYPE = 4;
	/**
	 * Ошибка чтения файла при загрузке изображения
	 */
	const UPLOAD_IMAGE_ERROR_READ = 8;
	
	/**
	 * Настройки модуля по умолчанию
	 *
	 * @var array
	 */
	protected $aParamsDefault = array();
	
	/**
	 * Тескт последней ошибки
	 *
	 * @var unknown_type
	 */
	protected $sLastErrorText = null;
	
	/**
	 * Инициализация модуля
	 */
	public function Init() {	
		$this->aParamsDefault = array(
			'watermark_use'=>false,
			'round_corner' =>false
		);
	}
	/**
	 * Получает текст последней ошибки
	 *
	 * @return unknown
	 */
	public function GetLastError() {
		return $this->sLastErrorText;
	}
	/**
	 * Устанавливает текст последней ошибки
	 *
	 * @param unknown_type $sText
	 */
	public function SetLastError($sText) {
		$this->sLastErrorText=$sText;
	}
	/**
	 * Очищает текст последней ошибки
	 *
	 */
	public function ClearLastError() {
		$this->sLastErrorText=null;
	}
	/**
	 * Merge default and named params for images
	 *
	 * @param  string $sName
	 * @return array
	 */
	public function BuildParams($sName=null) {
		if(is_null($sName)) {
			return Config::Get('module.image.default');
		}
		
		$aDefault = (array)Config::Get('module.image.default');
		$aNamed   = (array)Config::Get('module.image.'.strtolower($sName));
		
		return func_array_merge_assoc($aDefault,$aNamed);
	}
	/**
	 * Resize,copy image, 
	 * make rounded corners and add watermark
	 *
	 * @param  string $sFileSrc
	 * @param  string $sDirDest
	 * @param  string $sFileDest
	 * @param  int    $iWidthMax
	 * @param  int    $iHeightMax
	 * @param  int    $iWidthDest
	 * @param  int    $iHeightDest
	 * @param  bool   $bForcedMinSize
	 * @param  array  $aParams
	 * @param  object $oImage
	 * @return string
	 */
	public function Resize($sFileSrc,$sDirDest,$sFileDest,$iWidthMax,$iHeightMax,$iWidthDest=null,$iHeightDest=null,$bForcedMinSize=true,$aParams=null,$oImage=null) {
		$this->ClearLastError();
		/**
		 * Если параметры не переданы, устанавливаем действия по умолчанию
		 */
		if(!is_array($aParams)) {
			$aParams=$this->aParamsDefault;
		}
		/**
		 * Если объект не передан как параметр, 
		 * создаем новый
		 */
		if(!$oImage) $oImage=new LiveImage($sFileSrc);
		
		if($oImage->get_last_error()){
			$this->SetLastError($oImage->get_last_error());
			return false;
		}

		$sFileDest.='.'.$oImage->get_image_params('format');
		if (($oImage->get_image_params('width')>$iWidthMax) 
			or ($oImage->get_image_params('height')>$iHeightMax)) {
				return false;
		}
		$sFileFullPath=rtrim(Config::Get('path.root.server'),"/").'/'.trim($sDirDest,"/").'/'.$sFileDest;
		$this->CreateDirectory($sDirDest);
			
		if ($iWidthDest) {
			if ($bForcedMinSize and ($iWidthDest>$oImage->get_image_params('width'))) {
				$iWidthDest=$oImage->get_image_params('width');
			}
			/**
			 * Ресайзим и выводим результат в файл.
			 * Если не задана новая высота, то применяем масштабирование.
			 * Если нужно добавить Watermark, то запрещаем ручное управление alfa-каналом
			 */
			$oImage->resize($iWidthDest,$iHeightDest,(!$iHeightDest),(!$aParams['watermark_use']));
			
			/**
			 * Добавляем watermark согласно в конфигурации заданым параметрам
			 */
			if($aParams['watermark_use']) {
				if ($oImage->get_image_params('width')>$aParams['watermark_min_width'] and $oImage->get_image_params('height')>$aParams['watermark_min_height']) {
					switch($aParams['watermark_type']) {
						default:
						case 'text':
							$oImage->set_font(
								$aParams['watermark_font_size'],  0,
								$aParams['path']['fonts'].$aParams['watermark_font'].'.ttf'
							);

							$oImage->watermark(
								$aParams['watermark_text'],
								explode(',',$aParams['watermark_position'],2),
								explode(',',$aParams['watermark_font_color']),
								explode(',',$aParams['watermark_back_color']),
								$aParams['watermark_font_alfa'],
								$aParams['watermark_back_alfa']
							);
							break;
						case 'image':
							$oImage->paste_image(
								$aParams['path']['watermarks'].$aParams['watermark_image'],
								true, explode(',',$aParams['watermark_position'],2)
							);
							break;
					}
				}
			}
			/**
			 * Скругляем углы
			 */
			if($aParams['round_corner']) {
				$oImage->round_corners($aParams['round_corner_radius'], $aParams['round_corner_rate']);
			}
			/**
			 * Для JPG формата устанавливаем output quality, если это предусмотрено в конфигурации
			 */
			if(isset($aParams['jpg_quality']) and $oImage->get_image_params('format')=='jpg') {
				$oImage->set_jpg_quality($aParams['jpg_quality']);
			}
			
			$oImage->output(null,$sFileFullPath);
			
			chmod($sFileFullPath,0666);
			return $sFileFullPath;
		} elseif (copy($sFileSrc,$sFileFullPath)) {
			chmod($sFileFullPath,0666);
			return $sFileFullPath;
		}
		
		return false;
	}
	/**
	 * Вырезает максимально возможный квадрат
	 *
	 * @param  LiveImage $oImage
	 * @return LiveImage
	 */
	public function CropSquare(LiveImage $oImage) {
		if(!$oImage || $oImage->get_last_error()) {
			return false;
		}
		$iWidth  = $oImage->get_image_params('width');
		$iHeight = $oImage->get_image_params('height');
		/**
		 * Если высота и ширина совпадают, то возвращаем изначальный вариант
		 */
		if($iWidth==$iHeight){ return $oImage; }
		
		/**
		 * Вырезаем квадрат из центра
		 */
		$iNewSize = min($iWidth,$iHeight);
		$oImage->crop($iNewSize,$iNewSize,($iWidth-$iNewSize)/2,($iHeight-$iNewSize)/2);
		/**
		 * Возвращаем объект изображения
		 */
		return $oImage;
	}
	
	/**
	 * Создает каталог по указанному адресу (с учетом иерархии)
	 *
	 * @param string $sDirDest
	 */
	public function CreateDirectory($sDirDest) {
		@func_mkdir(Config::Get('path.root.server'),$sDirDest);		
	}
	/**
	 * Возвращает серверный адрес по переданному web-адресу
	 *
	 * @param  string $sPath
	 * @return string
	 */
	public function GetServerPath($sPath) {		
		/**
		 * Определяем, принадлежит ли этот адрес основному домену 
		 */
		if(parse_url($sPath,PHP_URL_HOST)!=parse_url(Config::Get('path.root.web'),PHP_URL_HOST)) {
			return $sPath;
		}
		/**
		 * Выделяем адрес пути
		 */
		$sPath = parse_url($sPath,PHP_URL_PATH);
		return rtrim(Config::Get('path.root.server'),'/').'/'.ltrim($sPath,'/');
	}
	/**
	 * Возвращает серверный адрес по переданному web-адресу
	 *
	 * @param  string $sPath
	 * @return string
	 */
	public function GetWebPath($sPath) {
		$sServerPath = rtrim(str_replace(DIRECTORY_SEPARATOR,'/',Config::Get('path.root.server')),'/');
		$sWebPath    = rtrim(Config::Get('path.root.web'), '/');
		return str_replace($sServerPath, $sWebPath, str_replace(DIRECTORY_SEPARATOR,'/',$sPath));
	}
	/**
	 * Получает директорию для данного пользователя
	 * Используется фомат хранения данных (/images/us/er/id/yyyy/mm/dd/file.jpg)
	 * 
	 * @param  string $sUserId
	 * @return string
	 */
	public function GetIdDir($sUserId) {
		return Config::Get('path.uploads.images').'/'.preg_replace('~(.{2})~U', "\\1/", str_pad($sUserId, 6, "0", STR_PAD_LEFT)).date('Y/m/d');
	}
	/**
	 * Возвращает валидный Html код тега <img>
	 *
	 * @param  string $sPath
	 * @param  array $aParams
	 * @return string
	 */
	public function BuildHTML($sPath,$aParams) {		
		$sText='<img src="'.$sPath.'" ';
		if (isset($aParams['title']) and $aParams['title']!='') {
			$sText.=' title="'.htmlspecialchars($aParams['title']).'" ';
			/**
			 * Если не определен ALT заполняем его тайтлом
			 */
			if(!isset($aParams['alt'])) $aParams['alt']=$aParams['title'];
		}
		if (isset($aParams['align']) and in_array($aParams['align'],array('left','right'))) {
			$sText.=' align="'.htmlspecialchars($aParams['align']).'" ';
		}
		$sAlt = isset($aParams['alt'])
			? ' alt="'.htmlspecialchars($aParams['alt']).'"'
			: ' alt=""';
		$sText.=$sAlt.' />';
		
		return $sText;
	}
	
	/**
	 * Завершение работы модуля
	 */
	public function Shutdown() {
	}
}
?>