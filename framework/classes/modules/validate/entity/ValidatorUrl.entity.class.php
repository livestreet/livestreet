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
 * CUrlValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор URL адресов
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorUrl extends ModuleValidate_EntityValidator {
	/**
	 * Патерн проверки URL с учетом схемы
	 *
	 * @var string
	 */
	public $pattern='/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
	/**
	 * Список допустимых схем
	 *
	 * @var array
	 **/
	public $validSchemes=array('http','https');
	/**
	 * Дефолтная схема, которая добавляется к URL при ее отсутствии.
	 * Если null, то URL должен уже содержать схему
	 *
	 * @var null|string
	 **/
	public $defaultScheme;
	/**
	 * Допускать или нет пустое значение
	 *
	 * @var bool
	 */
	public $allowEmpty=true;

	/**
	 * Запуск валидации
	 *
	 * @param mixed $sValue	Данные для валидации
	 *
	 * @return bool|string
	 */
	public function validate($sValue) {
		if (is_array($sValue)) {
			return $this->getMessage($this->Lang_Get('validate_url_not_valid',null,false),'msg');
		}
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}

		if(($sValue=$this->validateValue($sValue))!==false) {
			/**
			 * Если проверка от сущности, то возвращаем обновленное значение
			 */
			if ($this->oEntityCurrent) {
				$this->setValueOfCurrentEntity($this->sFieldCurrent,$sValue);
			}
		} else {
			return $this->getMessage($this->Lang_Get('validate_url_not_valid',null,false),'msg');
		}
		return true;
	}
	/**
	 * Проверка URL на корректность
	 *
	 * @param string $sValue Данные для валидации
	 *
	 * @return bool
	 */
	public function validateValue($sValue) {
		if(is_string($sValue) && strlen($sValue)<2000) {
			if($this->defaultScheme!==null && strpos($sValue,'://')===false) {
				$sValue=$this->defaultScheme.'://'.$sValue;
			}
			if(strpos($this->pattern,'{schemes}')!==false) {
				$sPattern=str_replace('{schemes}','('.implode('|',$this->validSchemes).')',$this->pattern);
			} else {
				$sPattern=$this->pattern;
			}
			if(preg_match($sPattern,$sValue)) {
				return $sValue;
			}
		}
		return false;
	}
}
?>