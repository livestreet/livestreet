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
 * Объект связи пользователя с разовором
 *
 * @package modules.talk
 * @since 1.0
 */
class ModuleTalk_EntityTalkUser extends Entity {
	/**
	 * Возвращает ID разговора
	 *
	 * @return int|null
	 */
	public function getTalkId() {
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
	 * Возвращает дату последнего сообщения
	 *
	 * @return string|null
	 */
	public function getDateLast() {
		return $this->_getDataOne('date_last');
	}
	/**
	 * Возвращает ID последнего комментария
	 *
	 * @return int|null
	 */
	public function getCommentIdLast() {
		return $this->_getDataOne('comment_id_last');
	}
	/**
	 * Возвращает количество новых сообщений
	 *
	 * @return int|null
	 */
	public function getCommentCountNew() {
		return $this->_getDataOne('comment_count_new');
	}

	/**
	 * Возвращает статус активности пользователя
	 *
	 * @return int
	 */
	public function getUserActive(){
		return $this->_getDataOne('talk_user_active') ? $this->_getDataOne('talk_user_active') : ModuleTalk::TALK_USER_ACTIVE;
	}
	/**
	 * Возвращает соответствующий пользователю объект
	 *
	 * @return ModuleUser_EntityUser | null
	 */
	public function getUser() {
		return $this->_getDataOne('user');
	}


	/**
	 * Устанавливает ID разговора
	 *
	 * @param int $data
	 */
	public function setTalkId($data) {
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
	 * Устанавливает последнюю дату
	 *
	 * @param string $data
	 */
	public function setDateLast($data) {
		$this->_aData['date_last']=$data;
	}
	/**
	 * Устанавливает ID последнее комментария
	 *
	 * @param int $data
	 */
	public function setCommentIdLast($data) {
		$this->_aData['comment_id_last']=$data;
	}
	/**
	 * Устанавливает количество новых комментариев
	 *
	 * @param int $data
	 */
	public function setCommentCountNew($data) {
		$this->_aData['comment_count_new']=$data;
	}
	/**
	 * Устанавливает статус связи
	 *
	 * @param int $data
	 */
	public function setUserActive($data) {
		$this->_aData['talk_user_active']=$data;
	}
	/**
	 * Устанавливает объект пользователя
	 *
	 * @param ModuleUser_EntityUser $data
	 */
	public function setUser($data) {
		$this->_aData['user']=$data;
	}
}
?>