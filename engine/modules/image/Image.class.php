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
class LsImage extends Module {
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
	 * Инициализация модуля
	 */
	public function Init() {	
		$this->aParamsDefault = array(
			'watermark_use'=>false,
			'round_corner' =>false
		);
	}
	/**
	 * Merge default and named params for images
	 *
	 * @param  string $sName
	 * @return array
	 */
	protected function BuildParams($sName=null) {
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
			return false;
		}
		$sFileDest.='.'.$oImage->get_image_params('format');
		if (($oImage->get_image_params('width')>$iWidthMax) 
			or ($oImage->get_image_params('height')>$iHeightMax)) {
				return false;
		}
		$sFileFullPath=Config::Get('path.root.server').'/'.$sDirDest.'/'.$sFileDest;
		@func_mkdir(Config::Get('path.root.server'),$sDirDest);
			
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
					
			/**
			 * Скругляем углы
			 */
			if($aParams['round_corner']) {
				$oImage->round_corners(
					$aParams['round_corner_radius'], 
					$aParams['round_corner_rate']
				);
			}
			
			$oImage->output(null,$sFileFullPath);
			
			chmod($sFileFullPath,0666);
			return $sFileDest;
		} elseif (copy($sFileSrc,$sFileFullPath)) {
			chmod($sFileFullPath,0666);
			return $sFileDest;
		}
		
		return false;
	}
	/**
	 * Upload user avatar on server
	 * Make resized images
	 *
	 * @param  array           $aFile
	 * @param  UserEntity_User $oUser
	 * @return (string|bool)
	 */
	public function UploadAvatar($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sFileTmp=$aFile['tmp_name'];
		$sPath = $this->GetUserDir($oUser);
		$aParams=$this->BuildParams('avatar');
		/**
		 * Срезаем квадрат
		 */
		$oImage = $this->CropSquare(new LiveImage($sFileTmp));
		
		if ($oImage && $sFileAvatar=$this->Resize($sFileTmp,$sPath,'avatar_100x100',3000,3000,100,100,true,$aParams,$oImage)) {
			$this->Resize($sFileTmp,$sPath,'avatar_64x64',3000,3000,64,64,true,$aParams,$oImage);
			$this->Resize($sFileTmp,$sPath,'avatar_48x48',3000,3000,48,48,true,$aParams,$oImage);
			$this->Resize($sFileTmp,$sPath,'avatar_24x24',3000,3000,24,24,true,$aParams,$oImage);
			$this->Resize($sFileTmp,$sPath,'avatar',3000,3000,null,null,true,$aParams,$oImage);
			
			/**
			 * Если все нормально, возвращаем расширение загруженного аватара
			 */
			return Config::Get('path.root.web').'/'.trim($sPath,'/').'/'.$sFileAvatar;
		}
		/**
		 * В случае ошибки, возвращаем false
		 */
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
		if($iWidth==$iHeight) return $oImage;
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
	 * Delete avatar from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteAvatar($oUser) {
		/**
		 * Если аватар есть, удаляем его и его рейсайзы
		 */
		if($oUser->getProfileAvatar()) {
			@unlink($this->GetServerPath($oUser->getProfileAvatarPath(100)));
			@unlink($this->GetServerPath($oUser->getProfileAvatarPath(64)));
			@unlink($this->GetServerPath($oUser->getProfileAvatarPath(48)));
			@unlink($this->GetServerPath($oUser->getProfileAvatarPath(24)));
			@unlink($this->GetServerPath($oUser->getProfileAvatarPath(0)));			
		}
	}
	/**
	 * Upload blog avatar on server
	 * Make resized images
	 *
	 * @param  array           $aFile
	 * @param  BlogEntity_Blog $oUser
	 * @return (string|bool)
	 */
	public function UploadBlogAvatar($aFile,$oBlog) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sFileTmp=$aFile['tmp_name'];
		$sPath=$this->GetUserDir($oBlog->getOwnerId());
		$aParams=$this->BuildParams('avatar');
		/**
		 * Срезаем квадрат
		 */
		$oImage = $this->CropSquare(new LiveImage($sFileTmp));
		
		if ($oImage && $sFileAvatar=$this->Resize($sFileTmp,$sPath,"avatar_blog_{$oBlog->getUrl()}_48x48",3000,3000,48,48,true,$aParams,$oImage)) {
			$this->Resize($sFileTmp,$sPath,"avatar_blog_{$oBlog->getUrl()}_24x24",3000,3000,24,24,true,$aParams,$oImage);
			$this->Resize($sFileTmp,$sPath,"avatar_blog_{$oBlog->getUrl()}",3000,3000,null,null,true,$aParams,$oImage);
			
			/**
			 * Если все нормально, возвращаем расширение загруженного аватара
			 */
			return Config::Get('path.root.web').'/'.trim($sPath,'/').'/'.$sFileAvatar;
		}
		/**
		 * В случае ошибки, возвращаем false
		 */
		return false;
	}
	/**
	 * Delete blog avatar from server
	 *
	 * @param BlogEntity_Blog $oUser
	 */
	public function DeleteBlogAvatar($oBlog) {
		/**
		 * Если аватар есть, удаляем его и его рейсайзы
		 */
		if($oBlog->getAvatar()) {		
			@unlink($this->GetServerPath($oBlog->getAvatarPath(48)));
			@unlink($this->GetServerPath($oBlog->getAvatarPath(24)));
			@unlink($this->GetServerPath($oBlog->getAvatarPath(0)));		
		}
	}
	
	/**
	 * Upload user foto
	 *
	 * @param  array           $aFile
	 * @param  UserEntity_User $oUser
	 * @return string
	 */
	public function UploadFoto($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sDirUpload=$this->GetUserDir($oUser->getId());			
		$sFileTmp=$aFile['tmp_name'];
		$aParams=$this->BuildParams('foto');
		
		if ($sFileFoto=$this->Resize($sFileTmp,$sDirUpload,func_generator(6),3000,3000,250,null,true,$aParams)) {
			/**
			 * удаляем старое фото
			 */
			$this->DeleteFoto($oUser);
			return Config::Get('path.root.web').'/'.ltrim($sDirUpload,'/').'/'.$sFileFoto;
		}
		return false;
	}
	/**
	 * Delete user foto from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteFoto($oUser) {
		@unlink($this->GetServerPath($oUser->getProfileFoto()));
	}
	/**
	 * Возвращает серверный адрес по переданному web-адресу
	 *
	 * @param  string $sPath
	 * @return string
	 */
	protected function GetServerPath($sPath) {
		return str_replace(Config::Get('path.root.web'), Config::Get('path.root.server'), $sPath);
	}
	/**
	 * Получает директорию для данного пользователя
	 * Используется фомат хранения данных (/images/u/s/e/r/i/d/yyyy/mm/dd/file.jpg)
	 * 
	 * @param  (object|string) $oUser
	 * @return string
	 */
	protected function GetUserDir($oUser) {
		$sUserId = is_object($oUser) ? $oUser->getId() : $oUser;
		return Config::Get('path.uploads.images').'/'.preg_replace('~(.)~U', "\\1/", str_pad($sUserId, 6, "0", STR_PAD_LEFT)).date('Y/m/d');
	}
	/**
	 * Заргузка изображений при написании топика
	 *
	 * @param  array           $aFile
	 * @param  UserEntity_User $oUser
	 * @return string|bool
	 */
	public function UploadTopicImageFile($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
		
		$sDirUpload=$this->GetUserDir($oUser->getId());			
		$sFileTmp=$aFile['tmp_name'];
		$aParams=$this->BuildParams('topic');
		
		if ($sFileImage=$this->Resize($sFileTmp,$sDirUpload,func_generator(6),3000,3000,Config::Get('view.img_resize_width'),null,true,$aParams)) {
			return $sDirUpload.'/'.$sFileImage;
		}
		return false;
	}
	/**
	 * Загрузка изображений по переданному URL
	 *
	 * @param  string          $sUrl
	 * @param  UserEntity_User $oUser
	 * @return (string|bool)
	 */
	public function UploadTopicImageUrl($sUrl, $oUser) {
		/**
		 * Проверяем, является ли файл изображением
		 */
		if(!@getimagesize($sUrl)) {
			return self::UPLOAD_IMAGE_ERROR_TYPE;
		}
		/**
		 * Открываем файловый поток и считываем файл поблочно,
		 * контролируя максимальный размер изображения
		 */
		$oFile=fopen($sUrl,'r');
		if(!$oFile) {
			return self::UPLOAD_IMAGE_ERROR_READ;
		}
		
		$iMaxSizeKb=500;
		$iSizeKb=0;
		$sContent='';
		while (!feof($oFile) and $iSizeKb<$iMaxSizeKb) {
			$sContent.=fread($oFile ,1024*1);
			$iSizeKb++;
		}

		/**
		 * Если конец файла не достигнут,
		 * значит файл имеет недопустимый размер
		 */
		if(!feof($oFile)) {
			return self::UPLOAD_IMAGE_ERROR_SIZE;
		}
		fclose($oFile);

		/**
		 * Создаем tmp-файл, для временного хранения изображения
		 */
		$sFileTmp=Config::Get('sys.cache.dir').func_generator();
		
		$fp=fopen($sFileTmp,'w');
		fwrite($fp,$sContent);
		fclose($fp);
		
		$sDirSave=$this->GetUserDir($oUser->getId());
		$aParams=$this->BuildParams('topic');
		
		/**
		 * Передаем изображение на обработку
		 */
		if ($sFileImg=$this->Resize($sFileTmp,$sDirSave,func_generator(),3000,3000,Config::Get('view.img_resize_width'),null,false,$aParams)) {
			@unlink($sFileTmp);
			return $sDirSave.'/'.$sFileImg;
		} 		
		
		@unlink($sFileTmp);
		return self::UPLOAD_IMAGE_ERROR;
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
		}
		if (isset($aParams['align']) and in_array($aParams['align'],array('left','right'))) {
			$sText.=' align="'.htmlspecialchars($aParams['align']).'" ';
		}
		$sAlt = isset($aParams['alt'])
			? ' alt=""'
			: ' alt="'.htmlspecialchars($aParams['alt']).'"';
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