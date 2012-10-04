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
 * CEmailValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор емайл адресов
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorEmail extends ModuleValidate_EntityValidator {
	/**
	 * Регулярное выражение для проверки емайла
	 *
	 * @var string
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
	/**
	 * Регулярное выражение для проверки емайла с именем отправителя.
	 * Используется только при allowName = true
	 *
	 * @var string
	 * @see allowName
	 */
	public $fullPattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';
	/**
	 * Учитывать при проверке имя отправителя, например, "Ivanov <ivanov@site.com>"
	 *
	 * @var bool
	 * @see fullPattern
	 */
	public $allowName=false;
	/**
	 * Производить проверку MX записи для емайла
	 *
	 * @var bool
	 */
	public $checkMX=false;
	/**
	 * Проверять 25 порт для емайла
	 *
	 * @var bool
	 */
	public $checkPort=false;
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
			return $this->getMessage($this->Lang_Get('validate_email_not_valid',null,false),'msg');
		}
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}
		if(!$this->validateValue($sValue)) {
			return $this->getMessage($this->Lang_Get('validate_email_not_valid',null,false),'msg');
		}
		return true;
	}
	/**
	 * Проверка емайла на корректность
	 *
	 * @param string $sValue	Данные для валидации
	 *
	 * @return bool
	 */
	public function validateValue($sValue) {
		$bValid=is_string($sValue) && strlen($sValue)<=254 && (preg_match($this->pattern,$sValue) || $this->allowName && preg_match($this->fullPattern,$sValue));
		if($bValid) {
			$sDomain=rtrim(substr($sValue,strpos($sValue,'@')+1),'>');
		}
		if($bValid && $this->checkMX && function_exists('checkdnsrr')) {
			$bValid=checkdnsrr($sDomain,'MX');
		}
		if($bValid && $this->checkPort && function_exists('fsockopen')) {
			$bValid=fsockopen($sDomain,25)!==false;
		}
		return $bValid;
	}
}
?>