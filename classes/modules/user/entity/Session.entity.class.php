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
 * Сущность сессии
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntitySession extends Entity {
	/**
	 * Возвращает ключ сессии
	 *
	 * @return string|null
	 */
	public function getKey() {
		return $this->_getDataOne('session_key');
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
	 * Возвращает IP создания сессии
	 *
	 * @return string|null
	 */
	public function getIpCreate() {
		return $this->_getDataOne('session_ip_create');
	}
	/**
	 * Возвращает последний IP сессии
	 *
	 * @return string|null
	 */
	public function getIpLast() {
		return $this->_getDataOne('session_ip_last');
	}
	/**
	 * Возвращает дату создания сессии
	 *
	 * @return string|null
	 */
	public function getDateCreate() {
		return $this->_getDataOne('session_date_create');
	}
	/**
	 * Возвращает последную дату сессии
	 *
	 * @return string|null
	 */
	public function getDateLast() {
		return $this->_getDataOne('session_date_last');
	}


	/**
	 * Устанавливает ключ сессии
	 *
	 * @param string $data
	 */
	public function setKey($data) {
		$this->_aData['session_key']=$data;
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
	 * Устанавливает IP создания сессии
	 *
	 * @param string $data
	 */
	public function setIpCreate($data) {
		$this->_aData['session_ip_create']=$data;
	}
	/**
	 * Устанавливает последний IP сессии
	 *
	 * @param string $data
	 */
	public function setIpLast($data) {
		$this->_aData['session_ip_last']=$data;
	}
	/**
	 * Устанавливает дату создания сессии
	 *
	 * @param string $data
	 */
	public function setDateCreate($data) {
		$this->_aData['session_date_create']=$data;
	}
	/**
	 * Устанавливает последную дату сессии
	 *
	 * @param string $data
	 */
	public function setDateLast($data) {
		$this->_aData['session_date_last']=$data;
	}
}
?>