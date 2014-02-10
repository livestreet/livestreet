<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Модуль управления медиа-данными (изображения, видео и т.п.)
 */
class ModuleMedia extends ModuleORM {
	/**
	 * Список типов медиа
	 * Свои кастомные типы необходимо нумеровать с 1000
	 */
	const TYPE_IMAGE=1;
	const TYPE_VIDEO=2;

	/**
	 * Объект текущего пользователя
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent;

	protected $oMapper=null;

	protected $aTargetTypes=array(
		'topic'=>array(),
		'comment'=>array(),
	);

	/**
	 * Список доступных типов медиа
	 *
	 * @var array
	 */
	protected $aMediaTypes=array(
		self::TYPE_IMAGE,self::TYPE_VIDEO
	);

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	/**
	 * Возвращает список типов объектов
	 *
	 * @return array
	 */
	public function GetTargetTypes() {
		return $this->aTargetTypes;
	}
	/**
	 * Добавляет в разрешенные новый тип
	 *
	 * @param string $sTargetType	Тип
	 * @param array $aParams	Параметры
	 * @return bool
	 */
	public function AddTargetType($sTargetType,$aParams=array()) {
		if (!array_key_exists($sTargetType,$this->aTargetTypes)) {
			$this->aTargetTypes[$sTargetType]=$aParams;
			return true;
		}
		return false;
	}
	/**
	 * Проверяет разрешен ли данный тип
	 *
	 * @param string $sTargetType	Тип
	 * @return bool
	 */
	public function IsAllowTargetType($sTargetType) {
		return in_array($sTargetType,array_keys($this->aTargetTypes));
	}
	/**
	 * Возвращает парметры нужного типа
	 *
	 * @param string $sTargetType
	 *
	 * @return mixed
	 */
	public function GetTargetTypeParams($sTargetType) {
		if ($this->IsAllowTargetType($sTargetType)) {
			return $this->aTargetTypes[$sTargetType];
		}
	}
	/**
	 * Проверяет разрешен ли тип медиа
	 *
	 * @param string $sType
	 *
	 * @return bool
	 */
	public function IsAllowMediaType($sType) {
		return in_array($sType,$this->aMediaTypes);
	}
	/**
	 * Проверка объекта target - владелец медиа
	 *
	 * @param string $sTargetType	Тип
	 * @param int $iTargetId	 ID владельца
	 * @return bool
	 */
	public function CheckTarget($sTargetType,$iTargetId) {
		if (!$this->IsAllowTargetType($sTargetType)) {
			return false;
		}
		$sMethod = 'CheckTarget'.func_camelize($sTargetType);
		if (method_exists($this,$sMethod)) {
			return $this->$sMethod($iTargetId);
		}
		return false;
	}

	public function Upload($aFile,$sTargetType,$sTargetId,$sTargetTmp=null) {
		if (is_string($aFile)) {
			return $this->UploadUrl($aFile,$sTargetType,$sTargetId,$sTargetTmp);
		} else {
			return $this->UploadLocal($aFile,$sTargetType,$sTargetId,$sTargetTmp);
		}
	}

	public function UploadLocal($aFile,$sTargetType,$sTargetId,$sTargetTmp=null) {
		if(!is_array($aFile) || !isset($aFile['tmp_name']) || !isset($aFile['name'])) {
			return false;
		}

		$aPathInfo=pathinfo($aFile['name']);
		$sExtension=isset($aPathInfo['extension']) ? $aPathInfo['extension'] : 'unknown';
		$sFileName = $aPathInfo['filename'].'.'.$sExtension;
		/**
		 * Копируем загруженный файл
		 */
		$sDirTmp=Config::Get('path.tmp.server').'/media/';
		if (!is_dir($sDirTmp)) {
			@mkdir($sDirTmp,0777,true);
		}
		$sFileTmp=$sDirTmp.$sFileName;
		if (!move_uploaded_file($aFile['tmp_name'],$sFileTmp)) {
			return 'Не удалось загрузить файл';
		}
		/**
		 * TODO: проверить на размер файла в байтах
		 */

		return $this->ProcessingFile($sFileTmp,$sTargetType,$sTargetId,$sTargetTmp);
	}

	public function UploadUrl($sFileUrl,$sTargetType,$sTargetId,$sTargetTmp=null) {
		/**
		 * Проверяем, является ли файл изображением
		 * TODO: файл может быть не только изображением, поэтому требуется рефакторинг
		 */
		if(!$aImageInfo=(@getimagesize($sFileUrl))) {
			return 'Файл не является изображением';
		}
		$aTypeImage=array(1=>'gif',2=>'jpg',3=>'png'); // see http://php.net/manual/en/function.exif-imagetype.php
		$sExtension=isset($aTypeImage[$aImageInfo[2]]) ? $aTypeImage[$aImageInfo[2]] : 'jpg';
		/**
		 * Открываем файловый поток и считываем файл поблочно,
		 * контролируя максимальный размер изображения
		 */
		$rFile=fopen($sFileUrl,'r');
		if(!$rFile) {
			return 'Не удалось загрузить файл';
		}

		$iMaxSizeKb=Config::Get('module.media.image_max_size_url');
		$iSizeKb=0;
		$sContent='';
		while (!feof($rFile) and $iSizeKb<$iMaxSizeKb) {
			$sContent.=fread($rFile ,1024*2);
			$iSizeKb++;
		}
		/**
		 * Если конец файла не достигнут,
		 * значит файл имеет недопустимый размер
		 */
		if(!feof($rFile)) {
			return 'Превышен максимальный размер файла: '.Config::Get('module.media.image_max_size_url').'Kb';
		}
		fclose($rFile);
		/**
		 * Копируем загруженный файл
		 */
		$sDirTmp=Config::Get('path.tmp.server').'/media/';
		if (!is_dir($sDirTmp)) {
			@mkdir($sDirTmp,0777,true);
		}
		$sFileTmp=$sDirTmp.func_generator().'.'.$sExtension;
		$rFile=fopen($sFileTmp,'w');
		fwrite($rFile,$sContent);
		fclose($rFile);

		return $this->ProcessingFile($sFileTmp,$sTargetType,$sTargetId,$sTargetTmp);
	}

	public function ProcessingFile($sFileTmp,$sTargetType,$sTargetId,$sTargetTmp=null) {
		/**
		 * Определяем тип файла по расширенияю и запускаем обработку
		 */
		$aPathInfo=pathinfo($sFileTmp);
		$sExtension=@strtolower($aPathInfo['extension']);
		if (in_array($sExtension,array('jpg','jpeg','gif','png'))) {
			return $this->ProcessingFileImage($sFileTmp,$sTargetType,$sTargetId,$sTargetTmp);
		}
		return 'Неверный тип файла';
	}

	public function ProcessingFileImage($sFileTmp,$sTargetType,$sTargetId,$sTargetTmp=null) {
		$aPathInfo=pathinfo($sFileTmp);
		$aParams=$this->Image_BuildParams('media.'.$sTargetType);
		$oImage =$this->Image_CreateImageObject($sFileTmp);
		/**
		 * Если объект изображения не создан, возвращаем ошибку
		 */
		if($sError=$oImage->get_last_error()) {
			@unlink($sFileTmp);
			return $sError;
		}
		/**
		 * Превышает максимальные размеры из конфига
		 */
		if (($oImage->get_image_params('width')>Config::Get('module.media.image_max_width')) or ($oImage->get_image_params('height')>Config::Get('module.media.image_max_height'))) {
			@unlink($sFileTmp);
			return 'Превышен максимальный размер изображения';
		}
		$iWidth=$oImage->get_image_params('width');
		$iHeight=$oImage->get_image_params('height');
		$sPath=$this->GetSaveDir($sTargetType,$sTargetId);
		if (!is_dir(Config::Get('path.root.server').$sPath)) {
			@mkdir(Config::Get('path.root.server').$sPath,0777,true);
		}
		/**
		 * Копируем файл в нужный каталог
		 */
		$sFileName=func_generator(20);
		$sFilePath=Config::Get('path.root.server').$sPath.$sFileName.'.'.$oImage->get_image_params('format');
		rename($sFileTmp,$sFilePath);

		$aSizes=Config::Get("module.media.type.{$sTargetType}.image_sizes");
		if (!$aSizes) {
			$aSizes=Config::Get("module.media.image_sizes");
		}
		foreach ($aSizes as $aSize) {
			/**
			 * Для каждого указанного в конфиге размера генерируем картинку
			 */
			$sNewFileName = $sFileName.'_'.$aSize['w'];
			$oImage = $this->Image_CreateImageObject($sFilePath);
			if ($aSize['crop']) {
				$this->Image_CropProportion($oImage, $aSize['w'], $aSize['h'], true);
				$sNewFileName .= 'crop';
			}
			$this->Image_Resize($sFilePath,$sPath,$sNewFileName,Config::Get('module.media.image_max_width'),Config::Get('module.media.image_max_height'),$aSize['w'],$aSize['h'],true,$aParams,$oImage);
		}
		/**
		 * Сохраняем медиа
		 */
		$oMedia=Engine::GetEntity('ModuleMedia_EntityMedia');
		$oMedia->setUserId($this->oUserCurrent ? $this->oUserCurrent->getId() : null);
		$oMedia->setType(self::TYPE_IMAGE);
		$oMedia->setFilePath($this->Image_GetWebPath($sFilePath));
		$oMedia->setFileName($aPathInfo['filename']);
		$oMedia->setFileSize(filesize($sFilePath));
		$oMedia->setWidth($iWidth);
		$oMedia->setHeight($iHeight);
		$oMedia->setDataOne('image_sizes',$aSizes);
		if ($oMedia->Add()) {
			/**
			 * Создаем связь с владельцем
			 */
			$oTarget=Engine::GetEntity('ModuleMedia_EntityTarget');
			$oTarget->setMediaId($oMedia->getId());
			$oTarget->setTargetType($sTargetType);
			$oTarget->setTargetId($sTargetId ? $sTargetId : null);
			$oTarget->setTargetTmp($sTargetTmp ? $sTargetTmp : null);
			if ($oTarget->Add()) {
				return $oMedia;
			}
		}
		return false;
	}
	/**
	 * Возвращает каталог для сохранения контента медиа
	 *
	 * @param string  $sTargetType
	 * @param string|null $sTargetId	Желательно для одного типа при формировании каталога для загрузки выбрать что-то одно - использовать $sTargetId или нет
	 *
	 * @return string
	 */
	public function GetSaveDir($sTargetType,$sTargetId=null) {
		return Config::Get('path.uploads.base')."/media/{$sTargetType}/".date('Y/m/d/H/');
	}

	public function BuildCodeForEditor($oMedia,$aParams) {
		$sCode='';
		if ($oMedia->getType()==self::TYPE_IMAGE) {
			$aSizes=(array)$oMedia->getDataOne('image_sizes');

			$sSizeParam=isset($aParams['size']) ? (string)$aParams['size'] : '';
			$sSize='original';
			$bNeedHref=false;
			/**
			 * Проверяем корректность размера
			 */
			foreach($aSizes as $aSizeAllow) {
				$sSizeKey=$aSizeAllow['w'].($aSizeAllow['crop'] ? 'crop' : '');
				if ($sSizeKey==$sSizeParam) {
					$sSize=$sSizeKey;
					/**
					 * Необходимость лайтбокса
					 */
					if ($aSizeAllow['w']<$oMedia->getWidth()) {
						$bNeedHref=true;
					}
				}
			}


			$sPath=$oMedia->getFileWebPath($sSize=='original' ? null : $sSize);

			$sCode='<img src="'.$sPath.'" ';
			if (!isset($aParams['title'])) {
				$aParams['title']=$oMedia->getDataOne('title');
			}
			if (!isset($aParams['skip_title']) and $aParams['title']) {
				$sCode.=' title="'.htmlspecialchars($aParams['title']).'" ';
				$sCode.=' alt="'.htmlspecialchars($aParams['title']).'" ';
			}
			if (isset($aParams['align']) and in_array($aParams['align'],array('left','right','center'))) {
				if ($aParams['align'] == 'center') {
					$sCode.=' class="image-center"';
				} else {
					$sCode.=' align="'.htmlspecialchars($aParams['align']).'" ';
				}
			}
			$sDataParams='';
			if (isset($aParams['data']) and is_array($aParams['data'])) {
				foreach($aParams['data'] as $sDataName=>$sDataValue) {
					if ($sDataValue) {
						$sDataParams.=' data-'.$sDataName.'="'.htmlspecialchars($sDataValue).'"';
					}
				}
			}
			if ($bNeedHref) {
				$sCode.=' />';
				$sLbxGroup='';
				if (isset($aParams['lbx_group'])) {
					$sLbxGroup=' data-rel="'.htmlspecialchars($aParams['lbx_group']).'"';
				}
				$sCode='<a class="js-lbx" '.$sLbxGroup.' href="'.$oMedia->getFileWebPath().'" '.$sDataParams.'>'.$sCode.'</a>';
			} else {
				$sCode.=$sDataParams.' />';
			}
		}

		return $sCode;
	}

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

	public function GetMediaByTarget($sTargetType,$iTargetId,$iUserId=null) {
		return $this->oMapper->GetMediaByTarget($sTargetType,$iTargetId,$iUserId);
	}

	public function GetMediaByTargetTmp($sTargetTmp,$iUserId=null) {
		return $this->oMapper->GetMediaByTargetTmp($sTargetTmp,$iUserId);
	}

	public function DeleteFile($oMedia) {
		/**
		 * Сначала удаляем все файлы
		 */
		if ($oMedia->getType()==self::TYPE_IMAGE) {
			$aSizes=$oMedia->getDataOne('image_sizes');
			foreach($aSizes as $aSize) {
				$sSize = $aSize['w'];
				if ($aSize['crop']) {
					$sSize.='crop';
				}
				$this->Image_RemoveFile($this->Image_GetServerPath($oMedia->getFileWebPath($sSize)));
			}
		}
		/**
		 * Удаляем все связи
		 */
		$aTargets=$oMedia->getTargets();
		foreach($aTargets as $oTarget) {
			$oTarget->Delete();
		}

		return $oMedia->Delete();
	}

	/**
	 * Возвращает список media с учетов прав доступа текущего пользователя
	 *
	 * @param array $aId
	 *
	 * @return array
	 */
	public function GetAllowMediaItemsById($aId) {
		$aIdItems=array();
		foreach((array)$aId as $iId) {
			$aIdItems[]=(int)$iId;
		}

		if (is_array($aIdItems) and count($aIdItems)) {
			$iUserId=$this->oUserCurrent ? $this->oUserCurrent->getId() : null;
			return $this->Media_GetMediaItemsByFilter(array(
														  '#where'=>array('id in (?a) AND ( user_id is null OR user_id = ?d )'=>array($aIdItems,$iUserId))
													  )
			);
		}
		return array();
	}

	/**
	 * Обработка тега gallery в тексте
	 * <pre>
	 * <gallery items="12,55,38" />
	 * </pre>
	 *
	 * @param string $sTag	Тег на ктором сработал колбэк
	 * @param array $aParams Список параметров тега
	 * @return string
	 */
	public function CallbackParserTagGallery($sTag,$aParams) {
		if (isset($aParams['items'])) {
			$aItems=explode(',',$aParams['items']);
		}

		if (!(isset($aItems) and $aMediaItems=$this->Media_GetAllowMediaItemsById($aItems))) {
			return '';
		}

		$aParamsMedia=array(
			'size'=>'100crop',
			'skip_title'=>true
		);
		$sProperties='';
		if (isset($aParams['nav']) and in_array($aParams['nav'],array('thumbs'))) {
			$sProperties.=' data-nav="'.$aParams['nav'].'" ';
		}
		$sTextResult='<div class="fotorama" '.$sProperties.'>'."\r\n";
		foreach($aMediaItems as $oMedia) {
			if (isset($aParams['caption']) and $aParams['caption']) {
				$aParamsMedia['data']['caption']=htmlspecialchars($oMedia->getDataOne('title'));
			}
			$sTextResult.="\t".$this->Media_BuildCodeForEditor($oMedia,$aParamsMedia)."\r\n";
		}
		$sTextResult.="</div>\r\n";
		return $sTextResult;
	}
	/**
	 * Заменяет временный идентификатор на необходимый ID объекта
	 *
	 * @param string	$sTargetType
	 * @param string	$sTargetId
	 * @param null|string	$sTargetTmp	Если не задан, то берется их куки "media_target_tmp_{$sTargetType}"
	 */
	public function ReplaceTargetTmpById($sTargetType,$sTargetId,$sTargetTmp=null) {
		$sCookieKey='media_target_tmp_'.$sTargetType;
		if (is_null($sTargetTmp) and isset($_COOKIE[$sCookieKey])) {
			$sTargetTmp=$_COOKIE[$sCookieKey];
			setcookie($sCookieKey,null,-1,Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
		}
		if (is_string($sTargetTmp)) {
			$aTargetItems=$this->Media_GetTargetItemsByTargetTmpAndTargetType($sTargetTmp,$sTargetType);
			foreach($aTargetItems as $oTarget) {
				$oTarget->setTargetTmp(null);
				$oTarget->setTargetId($sTargetId);
				$oTarget->Update();
			}
		}
	}



	/**
	 * Проверка владельца с типом "topic"
	 * Название метода формируется автоматически
	 *
	 * @param int $iTargetId	ID владельца
	 * @return bool
	 */
	public function CheckTargetTopic($iTargetId) {
		if ($oTopic=$this->Topic_GetTopicById($iTargetId)) {
			/**
			 * Проверяем права на редактирование топика
			 */
			if ($this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Проверка владельца с типом "comment"
	 * Название метода формируется автоматически
	 *
	 * @param int $iTargetId	ID владельца
	 * @return bool
	 */
	public function CheckTargetComment($iTargetId) {
		if ($oComment=$this->Comment_GetCommentById($iTargetId)) {
			/**
			 * Проверяем права на редактирование комментария
			 */
			if ($this->ACL_IsAllowEditComment($oComment,$this->oUserCurrent)) {
				return true;
			}
		}
		return false;
	}
}