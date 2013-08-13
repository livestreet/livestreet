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
 * Объект сущности прямого эфира
 *
 * @package modules.comment
 * @since 1.0
 */
class ModuleComment_EntityCommentOnline extends Entity {
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
	 * Возвращает ID комментария
	 *
	 * @return int|null
	 */
	public function getCommentId() {
		return $this->_getDataOne('comment_id');
	}
	/**
	 * Возвращает ID родителя владельца
	 *
	 * @return int
	 */
	public function getTargetParentId() {
		return $this->_getDataOne('target_parent_id') ? $this->_getDataOne('target_parent_id') : 0;
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
	 * Устанавливает ID комментария
	 *
	 * @param int $data
	 */
	public function setCommentId($data) {
		$this->_aData['comment_id']=$data;
	}
	/**
	 * Устанавливает ID родителя владельца
	 *
	 * @param int $data
	 */
	public function setTargetParentId($data) {
		$this->_aData['target_parent_id']=$data;
	}
}
?>