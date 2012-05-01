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
 * Валидатор для кастомных методов объектов
 * Валидация происходит через метод внешнего объекта
 *
 * @package engine.modules.validate
 * @since 1.0
 */
class ModuleValidate_EntityValidatorInline extends ModuleValidate_EntityValidator {
	/**
	 * Метод объекта для валидации, в него передаются параметры: $sValue и $aParam
	 *
	 * @var string
	 */
	public $method;
	/**
	 * Объект у которого будет вызван метод валидации, дляя сущности - это сам объект сущности
	 *
	 * @var LsObject object
	 */
	public $object;
	/**
	 * Список параметров для передачи в метод валидации
	 *
	 * @var array
	 */
	public $params;

	/**
	 * Запуск валидации
	 *
	 * @param mixed $sValue	Данные для валидации
	 * @return bool|string
	 */
	public function validate($sValue) {
		$sMethod=$this->method;
		return $this->object->$sMethod($sValue,$this->params);
	}
}
?>