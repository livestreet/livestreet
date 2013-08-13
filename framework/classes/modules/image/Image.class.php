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

require_once Config::Get('path.framework.libs_vendor.server').'/LiveImage/Image.php';

/**
 * Модуль обработки изображений
 * Использует библиотеку LiveImage
 *
 * @package engine.modules
 * @since 1.0
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
	 * @var string
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
	 * @return string
	 */
	public function GetLastError() {
		return $this->sLastErrorText;
	}
	/**
	 * Устанавливает текст последней ошибки
	 *
	 * @param string $sText	Текст ошибки
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
	 * Возврашает параметры для группы, если каких то параметров в группе нет, то используются дефолтные
	 *
	 * @param  string $sName	Имя группы
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
	 * Возвращает объект изображения
	 *
	 * @param $sFile	Путь до изображения
	 * @return LiveImage
	 */
	public function CreateImageObject($sFile) {
		return new LiveImage($sFile);
	}
	/**
	 * Resize,copy image,
	 * make rounded corners and add watermark
	 *
	 * @param  string $sFileSrc	Исходный файл изображения
	 * @param  string $sDirDest	Директория куда нужно сохранить изображение относительно корня сайта (path.root.server)
	 * @param  string $sFileDest	Имя файла для сохранения, без расширения
	 * @param  int    $iWidthMax	Максимально допустимая ширина изображения
	 * @param  int    $iHeightMax	Максимало допустимая высота изображения
	 * @param  int|null    $iWidthDest	Ширина необходимого изображения на выходе
	 * @param  int|null    $iHeightDest	Высота необходимого изображения на выходе
	 * @param  bool   $bForcedMinSize	Растягивать изображение по ширине или нет, если исходное меньше. При false - изображение будет растянуто
	 * @param  array|null  $aParams		Параметры
	 * @param  LiveImage|null $oImage	Объект изображения, если null то будет содано автоматически
	 * @return string|bool	Полный серверный путь до сохраненного изображения
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
		if(!$oImage) $oImage=$this->CreateImageObject($sFileSrc);

		if($oImage->get_last_error()){
			$this->SetLastError($oImage->get_last_error());
			return false;
		}

		$sFileDest.='.'.$oImage->get_image_params('format');
		if (($oImage->get_image_params('width')>$iWidthMax)
			or ($oImage->get_image_params('height')>$iHeightMax)) {
			return false;
		}

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
								false, explode(',',$aParams['watermark_position'],2)
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

			$sFileTmp=Config::Get('sys.cache.dir').func_generator(20);
			$oImage->output(null,$sFileTmp);
			return $this->SaveFile($sFileTmp,$sDirDest,$sFileDest,0666,true);
		} else{
			return $this->SaveFile($sFileSrc,$sDirDest,$sFileDest,0666,false);
		}
		return false;
	}
	/**
	 * Вырезает максимально возможный квадрат
	 *
	 * @param  LiveImage $oImage	Объект изображения
	 * @return LiveImage
	 */
	public function CropSquare(LiveImage $oImage,$bCenter=true) {
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

		if ($bCenter) {
			$oImage->crop($iNewSize,$iNewSize,($iWidth-$iNewSize)/2,($iHeight-$iNewSize)/2);
		} else {
			$oImage->crop($iNewSize,$iNewSize,0,0);
		}
		/**
		 * Возвращаем объект изображения
		 */
		return $oImage;
	}
	/**
	 * Вырезает максимально возможный прямоугольный в нужной пропорции
	 *
	 * @param LiveImage $oImage	Объект изображения
	 * @param int $iW	Ширина для определения пропорции
	 * @param int $iH	Высота для определения пропорции
	 * @param bool $bCenter	Вырезать из центра
	 * @return LiveImage
	 */
	public function CropProportion(LiveImage $oImage,$iW,$iH,$bCenter=true) {

		if(!$oImage || $oImage->get_last_error()) {
			return false;
		}
		$iWidth  = $oImage->get_image_params('width');
		$iHeight = $oImage->get_image_params('height');
		/**
		 * Если высота и ширина уже в нужных пропорциях, то возвращаем изначальный вариант
		 */
		$iProp=round($iW/$iH, 2);
		if(round($iWidth/$iHeight, 2)==$iProp){ return $oImage; }

		/**
		 * Вырезаем прямоугольник из центра
		 */
		if (round($iWidth/$iHeight, 2)<=$iProp) {
			$iNewWidth=$iWidth;
			$iNewHeight=round($iNewWidth/$iProp);
		} else {
			$iNewHeight=$iHeight;
			$iNewWidth=$iNewHeight*$iProp;
		}

		if ($bCenter) {
			$oImage->crop($iNewWidth,$iNewHeight,($iWidth-$iNewWidth)/2,($iHeight-$iNewHeight)/2);
		} else {
			$oImage->crop($iNewWidth,$iNewHeight,0,0);
		}
		/**
		 * Возвращаем объект изображения
		 */
		return $oImage;
	}
	/**
	 * Сохраняет(копирует) файл изображения на сервер
	 * Если переопределить данный метод, то можно сохранять изображения, например, на Amazon S3
	 *
	 * @param string $sFileSource	Полный путь до исходного файла
	 * @param string $sDirDest	Каталог для сохранения файла относительно корня сайта
	 * @param string $sFileDest	Имя файла для сохранения
	 * @param int|null $iMode	Права chmod для файла, например, 0777
	 * @param bool $bRemoveSource	Удалять исходный файл или нет
	 * @return bool | string
	 */
	public function SaveFile($sFileSource,$sDirDest,$sFileDest,$iMode=null,$bRemoveSource=false) {
		$sFileDestFullPath=rtrim(Config::Get('path.root.server'),"/").'/'.trim($sDirDest,"/").'/'.$sFileDest;
		$this->CreateDirectory($sDirDest);

		$bResult=copy($sFileSource,$sFileDestFullPath);
		if ($bResult and !is_null($iMode)) {
			chmod($sFileDestFullPath,$iMode);
		}
		if ($bRemoveSource) {
			unlink($sFileSource);
		}
		/**
		 * Если копирование прошло успешно, возвращаем новый серверный путь до файла
		 */
		if ($bResult) {
			return $sFileDestFullPath;
		}
		return false;
	}
	/**
	 * Удаление файла изображения
	 *
	 * @param string $sFile	Полный серверный путь до файла
	 * @return bool
	 */
	public function RemoveFile($sFile) {
		if (file_exists($sFile)) {
			return unlink($sFile);
		}
		return false;
	}
	/**
	 * Копирует файл изображения в локальную файловую систему
	 *
	 * @param string $sFileSource	Полный серверный путь до файла (может быть на удаленном сервере)
	 * @param string $sFileDistLocal	Полный серверный путь до локального файла
	 * @return bool
	 */
	public function CopyFileToLocal($sFileSource,$sFileDistLocal) {
		if (@copy($sFileSource,$sFileDistLocal)) {
			return true;
		}
		return false;
	}
	/**
	 * Создает каталог по указанному адресу (с учетом иерархии)
	 *
	 * @param string $sDirDest	Каталог относительно корня сайта
	 */
	public function CreateDirectory($sDirDest) {
		@func_mkdir(Config::Get('path.root.server'),$sDirDest);
	}
	/**
	 * Возвращает серверный адрес по переданному web-адресу
	 *
	 * @param  string $sPath	WEB адрес изображения
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
		$sPath = ltrim(parse_url($sPath,PHP_URL_PATH),'/');
		if($iOffset = Config::Get('path.offset_request_url')){
			$sPath = preg_replace('#^([^/]+/*){'.$iOffset.'}#msi', '', $sPath);
		}
		return rtrim(Config::Get('path.root.server'),'/').'/'.$sPath;
	}
	/**
	 * Возвращает WEB адрес по переданному серверному адресу
	 *
	 * @param  string $sPath	Серверный адрес(путь) изображения
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
	 * @param  int $sId	Целое число, обычно это ID пользователя
	 * @return string
	 */
	public function GetIdDir($sId) {
		return Config::Get('path.uploads.images').'/'.preg_replace('~(.{2})~U', "\\1/", str_pad($sId, 6, "0", STR_PAD_LEFT)).date('Y/m/d');
	}
	/**
	 * Возвращает валидный Html код тега <img>
	 *
	 * @param  string $sPath	WEB адрес изображения
	 * @param  array $aParams	Параметры
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
		if (isset($aParams['align']) and in_array($aParams['align'],array('left','right','center'))) {
			if ($aParams['align'] == 'center') {
				$sText.=' class="image-center"';
			} else {
				$sText.=' align="'.htmlspecialchars($aParams['align']).'" ';
			}
		}
		$sAlt = isset($aParams['alt'])
			? ' alt="'.htmlspecialchars($aParams['alt']).'"'
			: ' alt=""';
		$sText.=$sAlt.' />';

		return $sText;
	}
}
?>