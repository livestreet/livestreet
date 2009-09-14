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
	 * @param unknown_type $sFileSrc
	 * @param unknown_type $sDirDest
	 * @param unknown_type $sFileDest
	 * @param unknown_type $iWidthMax
	 * @param unknown_type $iHeightMax
	 * @param unknown_type $iWidthDest
	 * @param unknown_type $iHeightDest
	 * @param unknown_type $bForcedMinSize
	 * @param unknown_type $aParams
	 * @return unknown
	 */
	public function Resize($sFileSrc,$sDirDest,$sFileDest,$iWidthMax,$iHeightMax,$iWidthDest=null,$iHeightDest=null,$bForcedMinSize=true,$aParams=null) {
		/**
		 * Если параметры не переданы, устанавливаем действия по умолчанию
		 */
		if(!is_array($aParams)) {
			$aParams=$this->aParamsDefault;
		}
		
		$oImage=new LiveImage($sFileSrc);
		
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
			if (!$bForcedMinSize and ($iWidthDest>$oImage->get_image_params('width'))) {
				$iWidthDest=$oImage->get_image_params('width');
			}
			/**
			 * Ресайзим и выводим результат в файл.
			 * Если не задана новая высота, то применяем масштабирование.
			 * Если нужно добавить Watermark, то запрещаем ручное управление alfa-каналом
			 */
			$oImage->resize($iWidthDest,$iHeightDest,(!$iHeightDest),(!Config::Get('module.image.watermark_use')));
	
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
		$sPath=Config::Get('path.uploads.images').'/'.$oUser->getId();
		$aParams=$this->BuildParams('avatar');
		
		if ($sFileAvatar=$this->Resize($sFileTmp,$sPath,'avatar_100x100',3000,3000,100,100,true,$aParams)) {
			$this->Resize($sFileTmp,$sPath,'avatar_64x64',3000,3000,64,64,true,$aParams);
			$this->Resize($sFileTmp,$sPath,'avatar_48x48',3000,3000,48,48,true,$aParams);
			$this->Resize($sFileTmp,$sPath,'avatar_24x24',3000,3000,24,24,true,$aParams);
			$this->Resize($sFileTmp,$sPath,'avatar',3000,3000,true,$aParams);
			
			/**
			 * Если все нормально, возвращаем расширение загруженного аватара
			 */
			$aFileInfo=pathinfo($sFileAvatar);
			return $aFileInfo['extension'];
		}
		/**
		 * В случае ошибки, возвращаем false
		 */
		return false;
	}
	/**
	 * Delete avatar from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteAvatar($oUser) {
		$sPath = Config::Get('path.root.server').Config::Get('path.uploads.images').'/'.$oUser->getId();
		/**
		 * Удаляем аватар и его рейсайзы
		 */
		@unlink($sPath.'/avatar_100x100.'.$oUser->getProfileAvatarType());
		@unlink($sPath.'/avatar_64x64.'.$oUser->getProfileAvatarType());
		@unlink($sPath.'/avatar_48x48.'.$oUser->getProfileAvatarType());
		@unlink($sPath.'/avatar_24x24.'.$oUser->getProfileAvatarType());
		@unlink($sPath.'/avatar.'.$oUser->getProfileAvatarType());		
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
		
		$sDirUpload=Config::Get('path.uploads.images').'/'.func_generator(1).'/'.func_generator(1).'/'.func_generator(1).'/'.func_generator(1).'/'.$oUser->getId();			
		$sFileTmp=$aFile['tmp_name'];
		$aParams=$this->BuildParams('foto');
		
		if ($sFileFoto=$this->Resize($sFileTmp,$sDirUpload,func_generator(6),3000,3000,250,null,true,$aParams)) {
			/**
			 * удаляем старое фото
			 */
			$this->DeleteFoto($oUser);
			return $sDirUpload.'/'.$sFileFoto;
		}
		return false;
	}
	/**
	 * Delete user foto from server
	 *
	 * @param UserEntity_User $oUser
	 */
	public function DeleteFoto($oUser) {
		@unlink(Config::Get('path.root.server').$oUser->getProfileFoto());
	}
	
	/**
	 * Завершение работы модуля
	 */
	public function Shutdown() {
	}
}
?>