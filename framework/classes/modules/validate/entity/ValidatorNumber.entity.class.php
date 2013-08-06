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
 * CNumberValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор числовых значений
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorNumber extends ModuleValidate_EntityValidator {
	/**
	 * Допускать только целое число
	 *
	 * @var bool
	 */
	public $integerOnly=false;
	/**
	 * Допускать или нет пустое значение
	 *
	 * @var bool
	 */
	public $allowEmpty=true;
	/**
	 * Максимально допустимое значение
	 *
	 * @var null|integer|float
	 */
	public $max;
	/**
	 * Минимально допустимое значение
	 *
	 * @var null|integer|float
	 */
	public $min;
	/**
	 * Кастомное сообщение об ошибке при слишком большом числе
	 *
	 * @var string
	 */
	public $msgTooBig;
	/**
	 * Кастомное сообщение об ошибке при слишком маленьком числе
	 *
	 * @var string
	 */
	public $msgTooSmall;
	/**
	 * Регулярное выражение для целого числа
	 *
	 * @var string
	 */
	public $integerPattern='/^\s*[+-]?\d+\s*$/';
	/**
	 * Регулярное выражение для числа, допускается дробное
	 *
	 * @var string
	 */
	public $numberPattern='/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

	/**
	 * Запуск валидации
	 *
	 * @param mixed $sValue	Данные для валидации
	 *
	 * @return bool|string
	 */
	public function validate($sValue) {
		if (is_array($sValue)) {
			return $this->getMessage($this->Lang_Get('validate_number_must_number',null,false),'msg');
		}
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}
		if($this->integerOnly) {
			if(!preg_match($this->integerPattern,"$sValue")) {
				return $this->getMessage($this->Lang_Get('validate_number_must_integer',null,false),'msg');
			}
		} else {
			if(!preg_match($this->numberPattern,"$sValue")) {
				return $this->getMessage($this->Lang_Get('validate_number_must_number',null,false),'msg');
			}
		}
		if($this->min!==null && $sValue<$this->min) {
			return $this->getMessage($this->Lang_Get('validate_number_too_small',null,false),'msgTooSmall',array('min'=>$this->min));
		}
		if($this->max!==null && $sValue>$this->max) {
			return $this->getMessage($this->Lang_Get('validate_number_too_big',null,false),'msgTooBig',array('max'=>$this->max));
		}
		return true;
	}
}
?>