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
 * Сущность дружбу - связи пользователей друг с другом
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntityFriend extends Entity {
	/**
	 * При переданном параметре $sUserId возвращает тот идентификатор,
	 * который не равен переданному
	 *
	 * @param string|null	$sUserId	ID пользователя
	 * @return string
	 */
	public function getFriendId($sUserId=null) {
		if(!$sUserId) {
			$sUserId=$this->getUserId();
		}
		if($this->_getDataOne('user_from')==$sUserId) {
			return $this->_aData['user_to'];
		}
		if($this->_getDataOne('user_to')==$sUserId) {
			return $this->_aData['user_from'];
		}
		return false;
	}
	/**
	 * Получает идентификатор пользователя,
	 * относительно которого был сделан запрос
	 *
	 * @return int
	 */
	public function getUserId() {
		return $this->_getDataOne('user');
	}
	/**
	 * Возвращает ID пользователя, который приглашает в друзья
	 *
	 * @return int|null
	 */
	public function getUserFrom() {
		return $this->_getDataOne('user_from');
	}
	/**
	 * Возвращает ID пользователя, которого пришлашаем в друзья
	 *
	 * @return int|null
	 */
	public function getUserTo() {
		return $this->_getDataOne('user_to');
	}
	/**
	 * Возвращает статус заявки на добавления в друзья у отправителя
	 *
	 * @return int|null
	 */
	public function getStatusFrom() {
		return $this->_getDataOne('status_from');
	}
	/**
	 * Возвращает статус заявки на добавления в друзья у получателя
	 *
	 * @return int|null
	 */
	public function getStatusTo() {
		return $this->_getDataOne('status_to') ? $this->_getDataOne('status_to') : ModuleUser::USER_FRIEND_NULL;
	}
	/**
	 * Возвращает статус дружбы
	 *
	 * @return int|null
	 */
	public function getFriendStatus() {
		return $this->getStatusFrom()+$this->getStatusTo();
	}
	/**
	 * Возвращает статус дружбы для конкретного пользователя
	 *
	 * @param int $sUserId	ID пользователя
	 * @return bool|int
	 */
	public function getStatusByUserId($sUserId) {
		if($sUserId==$this->getUserFrom()) {
			return $this->getStatusFrom();
		}
		if($sUserId==$this->getUserTo()) {
			return $this->getStatusTo();
		}
		return false;
	}

	/**
	 * Устанавливает ID пользователя, который приглашает в друзья
	 *
	 * @param int $data
	 */
	public function setUserFrom($data) {
		$this->_aData['user_from']=$data;
	}
	/**
	 * Устанавливает ID пользователя, которого пришлашаем в друзья
	 *
	 * @param int $data
	 */
	public function setUserTo($data) {
		$this->_aData['user_to']=$data;
	}
	/**
	 * Устанавливает статус заявки на добавления в друзья у отправителя
	 *
	 * @param int $data
	 */
	public function setStatusFrom($data) {
		$this->_aData['status_from']=$data;
	}
	/**
	 * Возвращает статус заявки на добавления в друзья у получателя
	 *
	 * @param int $data
	 */
	public function setStatusTo($data) {
		$this->_aData['status_to']=$data;
	}
	/**
	 * Устанавливает ID пользователя
	 *
	 * @param int $data
	 */
	public function setUserId($data) {
		$this->_aData['user']=$data;
	}
	/**
	 * Возвращает статус дружбы для конкретного пользователя
	 *
	 * @param int $data	Статус
	 * @param int $sUserId	ID пользователя
	 * @return bool
	 */
	public function setStatusByUserId($data,$sUserId) {
		if($sUserId==$this->getUserFrom()) {
			$this->setStatusFrom($data);
			return true;
		}
		if($sUserId==$this->getUserTo()) {
			$this->setStatusTo($data);
			return true;
		}
		return false;
	}
}
?>