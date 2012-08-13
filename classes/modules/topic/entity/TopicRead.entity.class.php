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
 * Объект сущности факта прочтения топика
 *
 * @package modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopicRead extends Entity {
	/**
	 * Возвращает ID топика
	 *
	 * @return int|null
	 */
	public function getTopicId() {
		return $this->_getDataOne('topic_id');
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
	 * Возвращает дату прочтения
	 *
	 * @return string|null
	 */
	public function getDateRead() {
		return $this->_getDataOne('date_read');
	}
	/**
	 * Возвращает число комментариев в последнем прочтении топика
	 *
	 * @return int|null
	 */
	public function getCommentCountLast() {
		return $this->_getDataOne('comment_count_last');
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
	 * Устанавливает ID топика
	 *
	 * @param int $data
	 */
	public function setTopicId($data) {
		$this->_aData['topic_id']=$data;
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
	 * Устанавливает дату прочтения
	 *
	 * @param string $data
	 */
	public function setDateRead($data) {
		$this->_aData['date_read']=$data;
	}
	/**
	 * Устанавливает число комментариев в последнем прочтении топика
	 *
	 * @param int $data
	 */
	public function setCommentCountLast($data) {
		$this->_aData['comment_count_last']=$data;
	}
	/**
	 * Устанавливает ID последнего комментария
	 *
	 * @param int $data
	 */
	public function setCommentIdLast($data) {
		$this->_aData['comment_id_last']=$data;
	}
}
?>