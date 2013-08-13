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
 * Объект сущности комментариев
 *
 * @package modules.comment
 * @since 1.0
 */
class ModuleComment_EntityComment extends Entity {
	/**
	 * Возвращает ID коммента
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->_getDataOne('comment_id');
	}
	/**
	 * Возвращает ID родительского коммента
	 *
	 * @return int|null
	 */
	public function getPid() {
		return $this->_getDataOne('comment_pid');
	}
	/**
	 * Возвращает значение left для дерева nested set
	 *
	 * @return int|null
	 */
	public function getLeft() {
		return $this->_getDataOne('comment_left');
	}
	/**
	 * Возвращает значение right для дерева nested set
	 *
	 * @return int|null
	 */
	public function getRight() {
		return $this->_getDataOne('comment_right');
	}
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
	 * Возвращет ID родителя владельца
	 *
	 * @return int|null
	 */
	public function getTargetParentId() {
		return $this->_getDataOne('target_parent_id') ? $this->_getDataOne('target_parent_id') : 0;
	}
	/**
	 * Возвращает ID пользователя, автора комментария
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->_getDataOne('user_id');
	}
	/**
	 * Возвращает текст комментария
	 *
	 * @return string|null
	 */
	public function getText() {
		return $this->_getDataOne('comment_text');
	}
	/**
	 * Возвращает дату комментария
	 *
	 * @return string|null
	 */
	public function getDate() {
		return $this->_getDataOne('comment_date');
	}
	/**
	 * Возвращает IP пользователя
	 *
	 * @return string|null
	 */
	public function getUserIp() {
		return $this->_getDataOne('comment_user_ip');
	}
	/**
	 * Возвращает рейтинг комментария
	 *
	 * @return string
	 */
	public function getRating() {
		return number_format(round($this->_getDataOne('comment_rating'),2), 0, '.', '');
	}
	/**
	 * Возвращает количество проголосовавших
	 *
	 * @return int|null
	 */
	public function getCountVote() {
		return $this->_getDataOne('comment_count_vote');
	}
	/**
	 * Возвращает флаг удаленного комментария
	 *
	 * @return int|null
	 */
	public function getDelete() {
		return $this->_getDataOne('comment_delete');
	}
	/**
	 * Возвращает флаг опубликованного комментария
	 *
	 * @return int
	 */
	public function getPublish() {
		return $this->_getDataOne('comment_publish') ? 1 : 0;
	}
	/**
	 * Возвращает хеш комментария
	 *
	 * @return string|null
	 */
	public function getTextHash() {
		return $this->_getDataOne('comment_text_hash');
	}

	/**
	 * Возвращает уровень вложенности комментария
	 *
	 * @return int|null
	 */
	public function getLevel() {
		return $this->_getDataOne('comment_level');
	}
	/**
	 * Проверяет является ли комментарий плохим
	 *
	 * @return bool
	 */
	public function isBad() {
		if ($this->getRating()<=Config::Get('module.comment.bad')) {
			return true;
		}
		return false;
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
	 * Возвращает объект владельца
	 *
	 * @return mixed|null
	 */
	public function getTarget() {
		return $this->_getDataOne('target');
	}
	/**
	 * Возвращает объект голосования
	 *
	 * @return ModuleVote_EntityVote|null
	 */
	public function getVote() {
		return $this->_getDataOne('vote');
	}
	/**
	 * Проверяет является ли комментарий избранным у текущего пользователя
	 *
	 * @return bool|null
	 */
	public function getIsFavourite() {
		return $this->_getDataOne('comment_is_favourite');
	}
	/**
	 * Возвращает количество избранного
	 *
	 * @return int|null
	 */
	public function getCountFavourite() {
		return $this->_getDataOne('comment_count_favourite');
	}



	/**
	 * Устанавливает ID комментария
	 *
	 * @param int $data
	 */
	public function setId($data) {
		$this->_aData['comment_id']=$data;
	}
	/**
	 * Устанавливает ID родительского комментария
	 *
	 * @param int $data
	 */
	public function setPid($data) {
		$this->_aData['comment_pid']=$data;
	}
	/**
	 * Устанавливает значени left для дерева nested set
	 *
	 * @param int $data
	 */
	public function setLeft($data) {
		$this->_aData['comment_left']=$data;
	}
	/**
	 * Устанавливает значени right для дерева nested set
	 *
	 * @param int $data
	 */
	public function setRight($data) {
		$this->_aData['comment_right']=$data;
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
	 * Устанавливает ID родителя владельца
	 *
	 * @param int $data
	 */
	public function setTargetParentId($data) {
		$this->_aData['target_parent_id']=$data;
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
	 * Устанавливает текст комментария
	 *
	 * @param string $data
	 */
	public function setText($data) {
		$this->_aData['comment_text']=$data;
	}
	/**
	 * Устанавливает дату комментария
	 *
	 * @param string $data
	 */
	public function setDate($data) {
		$this->_aData['comment_date']=$data;
	}
	/**
	 * Устанвливает IP пользователя
	 *
	 * @param string $data
	 */
	public function setUserIp($data) {
		$this->_aData['comment_user_ip']=$data;
	}
	/**
	 * Устанавливает рейтинг комментария
	 *
	 * @param float $data
	 */
	public function setRating($data) {
		$this->_aData['comment_rating']=$data;
	}
	/**
	 * Устанавливает количество проголосавших
	 *
	 * @param int $data
	 */
	public function setCountVote($data) {
		$this->_aData['comment_count_vote']=$data;
	}
	/**
	 * Устанавливает флаг удаленности комментария
	 *
	 * @param int $data
	 */
	public function setDelete($data) {
		$this->_aData['comment_delete']=$data;
	}
	/**
	 * Устанавливает флаг публикации
	 *
	 * @param int $data
	 */
	public function setPublish($data) {
		$this->_aData['comment_publish']=$data;
	}
	/**
	 * Устанавливает хеш комментария
	 *
	 * @param strign $data
	 */
	public function setTextHash($data) {
		$this->_aData['comment_text_hash']=$data;
	}

	/**
	 * Устанавливает уровень вложенности комментария
	 *
	 * @param int $data
	 */
	public function setLevel($data) {
		$this->_aData['comment_level']=$data;
	}
	/**
	 * Устаналвает объект пользователя
	 *
	 * @param ModuleUser_EntityUser $data
	 */
	public function setUser($data) {
		$this->_aData['user']=$data;
	}
	/**
	 * Устанавливает объект владельца
	 *
	 * @param mixed $data
	 */
	public function setTarget($data) {
		$this->_aData['target']=$data;
	}
	/**
	 * Устанавливает объект голосования
	 *
	 * @param ModuleVote_EntityVote $data
	 */
	public function setVote($data) {
		$this->_aData['vote']=$data;
	}
	/**
	 * Устанавливает факт нахождения комментария в избранном у текущего пользователя
	 *
	 * @param bool $data
	 */
	public function setIsFavourite($data) {
		$this->_aData['comment_is_favourite']=$data;
	}
	/**
	 * Устанавливает количество избранного
	 *
	 * @param int $data
	 */
	public function setCountFavourite($data) {
		$this->_aData['comment_count_favourite']=$data;
	}
}
?>