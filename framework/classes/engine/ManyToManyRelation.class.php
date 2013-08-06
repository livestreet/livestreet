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
 * Класс представляющий собой обёертку для связей MANY_TO_MANY.
 * Позволяет оперировать коллекцией загруженных по связи элементов через имя связи
 * Например
 * <pre>
 * $oTopic->Tags->add($oTag)
 * // или
 * $oTopic->Tags->delete($oTag->getId())
 * </pre> при
 * наличии настроенной MANY_TO_MANY связи 'tags'
 *
 * @package engine.orm
 * @since 1.0
 */
class LS_ManyToManyRelation extends LsObject {
	/**
	 * Список объектов связи
	 *
	 * @var array
	 */
	protected $_aCollection = array();
	/**
	 * Флаг обновления списка объектов связи
	 *
	 * @var bool
	 */
	protected $bUpdated = false;

	/**
	 * Устанавливает список объектов
	 *
	 * @param $aCollection	Список объектов связи
	 */
	public function __construct($aCollection) {
		$this->_aCollection = $aCollection;
	}
	/**
	 * Добавление объекта в список
	 *
	 * @param Entity $oEntity
	 */
	public function add($oEntity) {
		$this->bUpdated = true;
		$this->_aCollection[$oEntity->_getPrimaryKeyValue()] = $oEntity;
	}
	/**
	 * Удаление объекта из списка по его id или массиву id
	 *
	 * @param int|array $iId
	 */
	public function delete($iId) {
		$this->bUpdated = true;
		if (is_array($iId)) {
			foreach ($iId as $id) {
				if (isset($this->_aCollection[$id])) {
					unset($this->_aCollection[$id]);
				}
			}
		} elseif (isset($this->_aCollection[$iId])) {
			unset($this->_aCollection[$iId]);
		}
	}
	/**
	 * Удаляет все объекты
	 */
	public function clear() {
		$this->bUpdated = true;
		$this->_aCollection=array();
	}
	/**
	 * Возвращает список объектов связи
	 *
	 * @return array
	 */
	public function getCollection() {
		return $this->_aCollection;
	}
	/**
	 * Проверка списка на обновление
	 *
	 * @return bool
	 */
	public function isUpdated() {
		return $this->bUpdated;
	}
}