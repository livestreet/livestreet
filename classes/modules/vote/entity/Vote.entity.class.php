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
 * Сущность голосования
 *
 * @package modules.vote
 * @since 1.0
 */
class ModuleVote_EntityVote extends Entity {
	/**
	 * Возвращает ID владельца
	 *
	 * @return int|null
	 */
	public function getTargetId() {
		return $this->_getDataOne('target_id');
	}
	/**
	 * Возвращает тип владельца
	 *
	 * @return string|null
	 */
	public function getTargetType() {
		return $this->_getDataOne('target_type');
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
	 * Возвращает направление голоса: 0, 1, -1
	 *
	 * @return int|null
	 */
	public function getDirection() {
		return $this->_getDataOne('vote_direction');
	}
	/**
	 * Возвращает значение при голосовании
	 *
	 * @return float|null
	 */
	public function getValue() {
		return $this->_getDataOne('vote_value');
	}
	/**
	 * Возвращает дату голосования
	 *
	 * @return string|null
	 */
	public function getDate() {
		return $this->_getDataOne('vote_date');
	}
	/**
	 * Возвращает IP голосовавшего
	 *
	 * @return string|null
	 */
	public function getIp() {
		return $this->_getDataOne('vote_ip');
	}


	/**
	 * Устанавливает ID владельца
	 *
	 * @param int $data
	 */
	public function setTargetId($data) {
		$this->_aData['target_id']=$data;
	}
	/**
	 * Устанавливает тип владельца
	 *
	 * @param string $data
	 */
	public function setTargetType($data) {
		$this->_aData['target_type']=$data;
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
	 * Устанавливает направление голоса: 0, 1, -1
	 *
	 * @param int $data
	 */
	public function setDirection($data) {
		$this->_aData['vote_direction']=$data;
	}
	/**
	 * Устанавливает значение при голосовании
	 *
	 * @param float $data
	 */
	public function setValue($data) {
		$this->_aData['vote_value']=$data;
	}
	/**
	 * Устанавливает дату голосования
	 *
	 * @param string $data
	 */
	public function setDate($data) {
		$this->_aData['vote_date']=$data;
	}
	/**
	 * Устанавливает IP голосовавшего
	 *
	 * @param string $data
	 */
	public function setIp($data) {
		$this->_aData['vote_ip']=$data;
	}
}
?>