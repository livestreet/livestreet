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

class ModuleProperty_EntityValueTypeImage extends ModuleProperty_EntityValueTypeFile {

	public function getValueForDisplay() {
		return $this->getFileFullName();
	}


	public function validate() {
		/**
		 * Выполняем стандартные проверки для типа "Файл"
		 */
		$bRes=parent::validate();

		$oValue=$this->getValueObject();
		$oProperty=$oValue->getProperty();

		$aValue=$this->getValueForValidate();
		if (isset($aValue['tmp_name'])) {
			if(!$aImageInfo=(@getimagesize($aValue['tmp_name']))) {
				return 'Файл не является изображением';
			}
			/**
			 * Проверяем на максимальную ширину
			 */
			if ($iWMax=$oProperty->getValidateRuleOne('width_max') and $iWMax<$aImageInfo[0]) {
				return 'Максимальная допустимая ширина изображения '.$iWMax.'px';
			}
			/**
			 * Проверяем на максимальную высоту
			 */
			if ($iHMax=$oProperty->getValidateRuleOne('height_max') and $iHMax<$aImageInfo[1]) {
				return 'Максимальная допустимая высота изображения '.$iHMax.'px';
			}
		}

		return $bRes;
	}


	public function beforeSaveValue() {
		$oValue=$this->getValueObject();
		$oProperty=$oValue->getProperty();
		if (!$aFile=$oValue->getDataOne('file_raw')) {
			return true;
		}
		$oValue->setDataOne('file_raw',null);
		/**
		 * Удаляем предыдущий файл
		 */
		if (isset($aFile['remove']) or isset($aFile['name'])) {
			if ($aFilePrev=$oValue->getDataOne('file')) {
				$this->Media_RemoveImageBySizes($aFilePrev['path'],$oValue->getDataOne('image_sizes'),true);

				$oValue->setDataOne('file',array());
				$oValue->setDataOne('image_sizes',array());
				$oValue->setValueVarchar(null);
			}
		}

		if (isset($aFile['name'])) {
			/**
			 * Выполняем загрузку файла
			 */
			$aPathInfo=pathinfo($aFile['name']);
			$sExtension=isset($aPathInfo['extension']) ? $aPathInfo['extension'] : 'unknown';
			$sFileName = func_generator(20);
			/**
			 * Копируем загруженный файл
			 */
			$sDirTmp=Config::Get('path.tmp.server').'/property/';
			if (!is_dir($sDirTmp)) {
				@mkdir($sDirTmp,0777,true);
			}
			$sFileTmp=$sDirTmp.$sFileName;

			if (move_uploaded_file($aFile['tmp_name'],$sFileTmp)) {
				$sDirSave=Config::Get('path.root.server').$oProperty->getSaveFileDir();
				if (!is_dir($sDirSave)) {
					@mkdir($sDirSave,0777,true);
				}
				$sFilePath=$sDirSave.$sFileName.'.'.$sExtension; // todo: получать из модуля Image
				/**
				 * Сохраняем файл
				 * TODO: заменить на модуль Image
				 */
				if ($sFilePathNew=$this->SaveFile($sFileTmp,$sFilePath,null,false)) {
					/**
					 * Сохраняем данные о файле
					 */
					$oValue->setDataOne('file',array(
						'path'=>$sFilePathNew,
						'size'=>filesize($sFilePath),
						'name'=>htmlspecialchars($aPathInfo['filename']),
						'extension'=>htmlspecialchars($aPathInfo['extension']),
					));
					$aSizes=$oProperty->getParam('sizes');
					/**
					 * Сохраняем размеры
					 */
					$oValue->setDataOne('image_sizes',$aSizes);
					/**
					 * Сохраняем уникальный ключ для доступа к файлу
					 */
					$oValue->setValueVarchar(func_generator(32));
					/**
					 * Генерируем ресайзы
					 */
					$aParams=$this->Image_BuildParams('property.'.$oProperty->getTargetType().'.'.$oProperty->getType().'.'.$oProperty->getCode());
					$this->Media_GenerateImageBySizes($sFileTmp,$oProperty->getSaveFileDir(),$sFileName,$aSizes,$aParams);

					$this->Fs_RemoveFileLocal($sFileTmp);
					return true;
				}
				$this->Fs_RemoveFileLocal($sFileTmp);
			}
		}
	}


	public function prepareValidateRulesRaw($aRulesRaw) {
		$aRules=array();
		$aRules['allowEmpty']=isset($aRulesRaw['allowEmpty']) ? false : true;

		if (isset($aRulesRaw['size_max']) and is_numeric($aRulesRaw['size_max'])) {
			$aRules['size_max']=(int)$aRulesRaw['size_max'];
		}
		if (isset($aRulesRaw['width_max']) and is_numeric($aRulesRaw['width_max'])) {
			$aRules['width_max']=(int)$aRulesRaw['width_max'];
		}
		if (isset($aRulesRaw['height_max']) and is_numeric($aRulesRaw['height_max'])) {
			$aRules['height_max']=(int)$aRulesRaw['height_max'];
		}
		return $aRules;
	}

	public function prepareParamsRaw($aParamsRaw) {
		$aParams=array();

		$aParams['sizes']=array();
		if (isset($aParamsRaw['sizes']) and is_array($aParamsRaw['sizes'])) {
			foreach($aParamsRaw['sizes'] as $sSize) {
				if ($sSize and preg_match('#^(\d+)?(x)?(\d+)?([a-z]{2,10})?$#Ui',$sSize)) {
					$aParams['sizes'][]=htmlspecialchars($sSize);
				}
			}
		}
		$aParams['types']=array();
		if (isset($aParamsRaw['types']) and is_array($aParamsRaw['types'])) {
			foreach($aParamsRaw['types'] as $sType) {
				if ($sType) {
					$aParams['types'][]=htmlspecialchars($sType);
				}
			}
		}

		return $aParams;
	}

	public function getParamsDefault() {
		return array(
			'sizes'=>array(
				'150x150crop'
			),
			'types'=>array(
				'jpg','jpeg','gif','png'
			)
		);
	}
}