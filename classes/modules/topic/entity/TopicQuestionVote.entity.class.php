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
 * Объект сущности голосования в топике-опросе
 *
 * @package modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopicQuestionVote extends Entity {
	/**
	 * Возвращает ID топика
	 *
	 * @return int|null
	 */
	public function getTopicId() {
		return $this->_getDataOne('topic_id');
	}
	/**
	 * Возвращает ID проголосовавшего пользователя
	 *
	 * @return int|null
	 */
	public function getVoterId() {
		return $this->_getDataOne('user_voter_id');
	}
	/**
	 * Возвращает номер варианта
	 *
	 * @return int|null
	 */
	public function getAnswer() {
		return $this->_getDataOne('answer');
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
	 * Устанавливает ID проголосовавшего пользователя
	 *
	 * @param int $data
	 */
	public function setVoterId($data) {
		$this->_aData['user_voter_id']=$data;
	}
	/**
	 * Устанавливает номер варианта
	 *
	 * @param int $data
	 */
	public function setAnswer($data) {
		$this->_aData['answer']=$data;
	}
}
?>