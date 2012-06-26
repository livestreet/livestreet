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
 * Сущность восстановления пароля
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntityReminder extends Entity {
	/**
	 * Возвращает код восстановления
	 *
	 * @return string|null
	 */
	public function getCode() {
		return $this->_getDataOne('reminder_code');
	}
	/**
	 * Возвращает ID пользователя
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->_getDataOne('user_id');
	}
	/**
	 * Возвращает дату создания
	 *
	 * @return string|null
	 */
	public function getDateAdd() {
		return $this->_getDataOne('reminder_date_add');
	}
	/**
	 * Возвращает дату использования
	 *
	 * @return string|null
	 */
	public function getDateUsed() {
		return $this->_getDataOne('reminder_date_used');
	}
	/**
	 * Возвращает дату завершения срока действия кода
	 *
	 * @return string|null
	 */
	public function getDateExpire() {
		return $this->_getDataOne('reminder_date_expire');
	}
	/**
	 * Возвращает статус использованости кода
	 *
	 * @return int|null
	 */
	public function getIsUsed() {
		return $this->_getDataOne('reminde_is_used');
	}

	/**
	 * Устанавливает код восстановления
	 *
	 * @param string $data
	 */
	public function setCode($data) {
		$this->_aData['reminder_code']=$data;
	}
	/**
	 * Устанавливает ID пользователя
	 *
	 * @param int $data
	 */
	public function setUserId($data) {
		$this->_aData['user_id']=$data;
	}
	/**
	 * Устанавливает дату создания
	 *
	 * @param string $data
	 */
	public function setDateAdd($data) {
		$this->_aData['reminder_date_add']=$data;
	}
	/**
	 * Устанавливает дату использования
	 *
	 * @param string $data
	 */
	public function setDateUsed($data) {
		$this->_aData['reminder_date_used']=$data;
	}
	/**
	 * Устанавливает дату завершения срока действия кода
	 *
	 * @param string $data
	 */
	public function setDateExpire($data) {
		$this->_aData['reminder_date_expire']=$data;
	}
	/**
	 * Устанавливает статус использованости кода
	 *
	 * @param int $data
	 */
	public function setIsUsed($data) {
		$this->_aData['reminde_is_used']=$data;
	}
}
?>