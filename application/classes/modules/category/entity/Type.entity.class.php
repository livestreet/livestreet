<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Сущность типа категории
 *
 * @package application.modules.category
 * @since 2.0
 */
class ModuleCategory_EntityType extends EntityORM
{

    protected $aRelations = array();

    /**
     * Выполняется перед сохранением
     *
     * @return bool
     */
    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateCreate(date("Y-m-d H:i:s"));
            } else {
                $this->setDateUpdate(date("Y-m-d H:i:s"));
            }
        }
        return $bResult;
    }

    /**
     * Возвращает список дополнительных параметров
     *
     * @return array|mixed
     */
    public function getParams()
    {
        $aData = @unserialize($this->_getDataOne('params'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    /**
     * Устанавливает список дополнительных параметров
     *
     * @param $aParams
     */
    public function setParams($aParams)
    {
        $this->_aData['params'] = @serialize($aParams);
    }

    /**
     * Возвращает конкретный параметр
     *
     * @param $sName
     *
     * @return null
     */
    public function getParam($sName)
    {
        $aParams = $this->getParams();
        return isset($aParams[$sName]) ? $aParams[$sName] : null;
    }
}