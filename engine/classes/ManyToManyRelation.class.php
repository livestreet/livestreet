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
 * Класс. представляющий собой обёертку для связей MANY_TO_MANY.
 * Позволяет оперровать коллекцией загруженных по связи эдементов через имя связи
 * Например, $oTopic->Tags->add($oTag) или $oTopic->Tags->delete($oTag->getId()) при
 * наличии настроенной MANY_TO_MANY связи 'tags'
 */
class LS_ManyToManyRelation
{
    // Ссылка на $oEntityORM->aRelationsData[<relation_name>],
    // где relation_name - имя сязи, которую представляет объект
    protected $_aCollection = array();
    protected $bUpdated = false;

    public function __construct($aCollection)
    {
        $this->_aCollection = $aCollection;
    }

    /**
     * Добавление объекта в коллекцию
     * @param <type> $oEntity
     */
    public function add($oEntity)
    {
        $this->bUpdated = true;
        $this->_aCollection[$oEntity->_getPrimaryKeyValue()] = $oEntity;
    }

    /**
     * Удаление объекта из коллекции по его id или массиву id
     * @param <type> $iId
     */
    public function delete($iId)
    {
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

    public function getCollection()
    {
        return $this->_aCollection;
    }

    public function isUpdated()
    {
        return $this->bUpdated;
    }
}