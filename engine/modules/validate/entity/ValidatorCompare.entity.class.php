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
 * CCompareValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор сравнения значений
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorCompare extends ModuleValidate_EntityValidator {
	/**
	 * Имя поля для сравнения
	 *
	 * @var string
	 */
	public $compareField;
	/**
	 * Значение для сравнения
	 *
	 * @var string
	 */
	public $compareValue;
	/**
	 * Название поля сущности для сравнения, используется в сообщениях об ошибках
	 *
	 * @var string
	 */
	public $compareLabel;
	/**
	 * Строгое сравнение
	 *
	 * @var bool
	 */
	public $strict=false;
	/**
	 * Допускать или нет пустое значение
	 *
	 * @var bool
	 */
	public $allowEmpty=false;
	/**
	 * Оператор для сравнения
	 * Доступны: '=' или '==', '!=', '>', '>=', '<', '<='
	 *
	 * @var string
	 */
	public $operator='=';

	/**
	 * Запуск валидации
	 *
	 * @param mixed $sValue	Данные для валидации
	 *
	 * @return bool|string
	 */
	public function validate($sValue) {
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}
		/**
		 * Определяем значение для сравнения
		 */
		if($this->compareValue!==null or !$this->oEntityCurrent) {
			$sCompareLabel=$sCompareValue=$this->compareValue;
		} else {
			$sCompareField=$this->compareField===null ? $this->sFieldCurrent.'_repeat' : $this->compareField;
			$sCompareValue=$this->getValueOfCurrentEntity($sCompareField);
			$sCompareLabel=is_null($this->compareLabel) ? $sCompareField : $this->compareLabel;
		}

		switch($this->operator) {
			case '=':
			case '==':
				if(($this->strict && $sValue!==$sCompareValue) || (!$this->strict && $sValue!=$sCompareValue)) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_repeated',null,false),'msg',array('compare_field'=>$sCompareLabel));
				}
				break;
			case '!=':
				if(($this->strict && $sValue===$sCompareValue) || (!$this->strict && $sValue==$sCompareValue)) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_not_equal',null,false),'msg',array('compare_field'=>$sCompareLabel,'compare_value'=>htmlspecialchars($sCompareValue)));
				}
				break;
			case '>':
				if($sValue<=$sCompareValue) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_greater',null,false),'msg',array('compare_field'=>$sCompareLabel,'compare_value'=>htmlspecialchars($sCompareValue)));
				}
				break;
			case '>=':
				if($sValue<$sCompareValue) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_greater_equal',null,false),'msg',array('compare_field'=>$sCompareLabel,'compare_value'=>htmlspecialchars($sCompareValue)));
				}
				break;
			case '<':
				if($sValue>=$sCompareValue) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_less',null,false),'msg',array('compare_field'=>$sCompareLabel,'compare_value'=>htmlspecialchars($sCompareValue)));
				}
				break;
			case '<=':
				if($sValue>$sCompareValue) {
					return $this->getMessage($this->Lang_Get('validate_compare_must_less_equal',null,false),'msg',array('compare_field'=>$sCompareLabel,'compare_value'=>htmlspecialchars($sCompareValue)));
				}
				break;
			default:
				return $this->getMessage($this->Lang_Get('validate_compare_invalid_operator',null,false),'msg',array('operator'=>$this->operator));
		}
		return true;
	}
}
?>