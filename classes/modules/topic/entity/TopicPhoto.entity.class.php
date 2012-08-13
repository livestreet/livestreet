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
 * Объект сущности фото в топике-фотосете
 *
 * @package modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopicPhoto extends Entity {
	/**
	 * Возвращает ID фото
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->_getDataOne('id');
	}
	/**
	 * Возвращает ID топика
	 *
	 * @return int|null
	 */
	public function getTopicId() {
		return $this->_getDataOne('topic_id');
	}
	/**
	 * Возвращает ключ временного владельца
	 *
	 * @return string|null
	 */
	public function getTargetTmp() {
		return $this->_getDataOne('target_tmp');
	}
	/**
	 * Возвращает описание фото
	 *
	 * @return string|null
	 */
	public function getDescription() {
		return $this->_getDataOne('description');
	}
	/**
	 * Вовзращает полный веб путь до фото
	 *
	 * @return mixed|null
	 */
	public function getPath() {
		return $this->_getDataOne('path');
	}
	/**
	 * Возвращает полный веб путь до фото определенного размера
	 *
	 * @param string|null $sWidth	Размер фото, например, '100' или '150crop'
	 * @return null|string
	 */
	public function getWebPath($sWidth = null) {
		if ($this->getPath()) {
			if ($sWidth) {
				$aPathInfo=pathinfo($this->getPath());
				return $aPathInfo['dirname'].'/'.$aPathInfo['filename'].'_'.$sWidth.'.'.$aPathInfo['extension'];
			} else {
				return $this->getPath();
			}
		} else {
			return null;
		}
	}

	/**
	 * Устанавливает ID топика
	 *
	 * @param int $iTopicId
	 */
	public function setTopicId($iTopicId) {
		$this->_aData['topic_id'] = $iTopicId;
	}
	/**
	 * Устанавливает ключ временного владельца
	 *
	 * @param string $sTargetTmp
	 */
	public function setTargetTmp($sTargetTmp) {
		$this->_aData['target_tmp'] = $sTargetTmp;
	}
	/**
	 * Устанавливает описание фото
	 *
	 * @param string $sDescription
	 */
	public function setDescription($sDescription) {
		$this->_aData['description'] = $sDescription;
	}
}