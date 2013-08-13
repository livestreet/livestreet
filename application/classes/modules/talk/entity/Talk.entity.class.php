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
 * Объект сущности сообщения
 *
 * @package modules.talk
 * @since 1.0
 */
class ModuleTalk_EntityTalk extends Entity {
	/**
	 * Возвращает ID сообщения
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->_getDataOne('talk_id');
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
	 * Вовзращает заголовок сообщения
	 *
	 * @return string|null
	 */
	public function getTitle() {
		return $this->_getDataOne('talk_title');
	}
	/**
	 * Возвращает текст сообщения
	 *
	 * @return string|null
	 */
	public function getText() {
		return $this->_getDataOne('talk_text');
	}
	/**
	 * Возвращает дату сообщения
	 *
	 * @return string|null
	 */
	public function getDate() {
		return $this->_getDataOne('talk_date');
	}
	/**
	 * Возвращает дату последнего сообщения
	 *
	 * @return string|null
	 */
	public function getDateLast() {
		return $this->_getDataOne('talk_date_last');
	}
	/**
	 * Возвращает ID последнего пользователя
	 *
	 * @return int|null
	 */
	public function getUserIdLast() {
		return $this->_getDataOne('talk_user_id_last');
	}
	/**
	 * Вовзращает IP пользователя
	 *
	 * @return string|null
	 */
	public function getUserIp() {
		return $this->_getDataOne('talk_user_ip');
	}
	/**
	 * Возвращает ID последнего комментария
	 *
	 * @return int|null
	 */
	public function getCommentIdLast() {
		return $this->_getDataOne('talk_comment_id_last');
	}
	/**
	 * Возвращает количество комментариев
	 *
	 * @return int|null
	 */
	public function getCountComment() {
		return $this->_getDataOne('talk_count_comment');
	}


	/**
	 * Возвращает последний текст(коммент) из письма, если комментов нет, то текст исходного сообщения
	 *
	 * @return string
	 */
	public function getTextLast() {
		if ($oComment=$this->getCommentLast()) {
			return $oComment->getText();
		}
		return $this->getText();
	}
	/**
	 * Возвращает список пользователей
	 *
	 * @return array|null
	 */
	public function getUsers() {
		return $this->_getDataOne('users');
	}
	/**
	 * Возвращает объект пользователя
	 *
	 * @return ModuleUser_EntityUser|null
	 */
	public function getUser() {
		return $this->_getDataOne('user');
	}
	/**
	 * Возвращает объект связи пользователя с сообщением
	 *
	 * @return ModuleTalk_EntityTalkUser|null
	 */
	public function getTalkUser() {
		return $this->_getDataOne('talk_user');
	}
	/**
	 * Возращает true, если разговор занесен в избранное
	 *
	 * @return bool
	 */
	public function getIsFavourite() {
		return $this->_getDataOne('talk_is_favourite');
	}
	/**
	 * Возращает пользователей разговора
	 *
	 * @return array
	 */
	public function getTalkUsers() {
		return $this->_getDataOne('talk_users');
	}


	/**
	 * Устанавливает ID сообщения
	 *
	 * @param int $data
	 */
	public function setId($data) {
		$this->_aData['talk_id']=$data;
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
	 * Устанавливает заголовок сообщения
	 *
	 * @param string $data
	 */
	public function setTitle($data) {
		$this->_aData['talk_title']=$data;
	}
	/**
	 * Устанавливает текст сообщения
	 *
	 * @param string $data
	 */
	public function setText($data) {
		$this->_aData['talk_text']=$data;
	}
	/**
	 * Устанавливает дату разговора
	 *
	 * @param string $data
	 */
	public function setDate($data) {
		$this->_aData['talk_date']=$data;
	}
	/**
	 * Устанавливает дату последнего сообщения в разговоре
	 *
	 * @param string $data
	 */
	public function setDateLast($data) {
		$this->_aData['talk_date_last']=$data;
	}
	/**
	 * Устанавливает ID последнего пользователя
	 *
	 * @param int $data
	 */
	public function setUserIdLast($data) {
		$this->_aData['talk_user_id_last']=$data;
	}
	/**
	 * Устанавливает IP пользователя
	 *
	 * @param string $data
	 */
	public function setUserIp($data) {
		$this->_aData['talk_user_ip']=$data;
	}
	/**
	 * Устанавливает ID последнего комментария
	 *
	 * @param string $data
	 */
	public function setCommentIdLast($data) {
		$this->_aData['talk_comment_id_last']=$data;
	}
	/**
	 * Устанавливает количество комментариев
	 *
	 * @param int $data
	 */
	public function setCountComment($data) {
		$this->_aData['talk_count_comment']=$data;
	}

	/**
	 * Устанавливает список пользователей
	 *
	 * @param array $data
	 */
	public function setUsers($data) {
		$this->_aData['users']=$data;
	}
	/**
	 * Устанавливает объект пользователя
	 *
	 * @param ModuleUser_EntityUser $data
	 */
	public function setUser($data) {
		$this->_aData['user']=$data;
	}
	/**
	 * Устанавливает объект связи
	 *
	 * @param ModuleTalk_EntityTalkUser $data
	 */
	public function setTalkUser($data) {
		$this->_aData['talk_user']=$data;
	}
	/**
	 * Устанавливает факт налиция разговора в избранном текущего пользователя
	 *
	 * @param bool $data
	 */
	public function setIsFavourite($data) {
		$this->_aData['talk_is_favourite']=$data;
	}
	/**
	 * Устанавливает список связей
	 *
	 * @param array $data
	 */
	public function setTalkUsers($data) {
		$this->_aData['talk_users']=$data;
	}
}
?>