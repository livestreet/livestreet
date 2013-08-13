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
 * CTypeValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * Валидатор типа данных
 * Для типа дата/время используется внешний валидатор DateTimeParser
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorType extends ModuleValidate_EntityValidator {
	/**
	 * Допустимый тип данных.
	 * Допустимые значения: 'string', 'integer', 'float', 'array', 'date', 'time' и 'datetime'.
	 *
	 * @var string
	 */
	public $type='string';
	/**
	 * Допустимый формат даты, актуально при type = date
	 *
	 * @var string
	 */
	public $dateFormat='dd-MM-yyyy';
	/**
	 * Допустимый формат времени, актуально при type = time
	 *
	 * @var string
	 */
	public $timeFormat='hh:mm';
	/**
	 * Допустимый формат даты со временем, актуально при type = datetime
	 *
	 * @var string
	 */
	public $datetimeFormat='dd-MM-yyyy hh:mm';
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
		if($this->allowEmpty && $this->isEmpty($sValue)) {
			return true;
		}

		require_once(Config::Get('path.framework.libs_vendor.server').'/DateTime/DateTimeParser.php');

		if($this->type==='integer') {
			$bValid=preg_match('/^[-+]?[0-9]+$/',trim($sValue));
		} else if($this->type==='float') {
			$bValid=preg_match('/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/',trim($sValue));
		} else if($this->type==='date') {
			$bValid=DateTimeParser::parse($sValue,$this->dateFormat,array('month'=>1,'day'=>1,'hour'=>0,'minute'=>0,'second'=>0))!==false;
		} else if($this->type==='time') {
			$bValid=DateTimeParser::parse($sValue,$this->timeFormat)!==false;
		} else if($this->type==='datetime') {
			$bValid=DateTimeParser::parse($sValue,$this->datetimeFormat, array('month'=>1,'day'=>1,'hour'=>0,'minute'=>0,'second'=>0))!==false;
		} else if($this->type==='array') {
			$bValid=is_array($sValue);
		} else {
			return true;
		}

		if(!$bValid) {
			return $this->getMessage($this->Lang_Get('validate_type_error',null,false),'msg',array('type'=>$this->type));
		}
		return true;
	}
}
?>