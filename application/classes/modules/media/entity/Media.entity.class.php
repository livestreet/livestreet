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
 * Сущность медиа данных (изображение, видео и т.п.)
 *
 * @package application.modules.media
 * @since 2.0
 */
class ModuleMedia_EntityMedia extends EntityORM
{

    protected $aValidateRules = array();

    protected $aRelations = array(
        'targets' => array(self::RELATION_TYPE_HAS_MANY, 'ModuleMedia_EntityTarget', 'media_id'),
    );

    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateAdd(date("Y-m-d H:i:s"));
            }
        }
        return $bResult;
    }

    protected function beforeDelete()
    {
        if ($bResult = parent::beforeDelete()) {
            /**
             * Удаляем все связи
             */
            $aTargets = $this->getTargets();
            foreach ($aTargets as $oTarget) {
                $oTarget->Delete();
            }
            /**
             * Удаляем все файлы медиа
             */
            $this->Media_DeleteFiles($this);
        }
        return $bResult;
    }

    /**
     * Возвращает URL до файла нужного размера, в основном используется для изображений
     *
     * @param null $sSize
     *
     * @return null
     */
    public function getFileWebPath($sSize = null)
    {
        if ($this->getFilePath()) {
            return $this->Media_GetFileWebPath($this, $sSize);
        } else {
            return null;
        }
    }

    public function getData()
    {
        $aData = @unserialize($this->_getDataOne('data'));
        if (!$aData) {
            $aData = array();
        }
        return $aData;
    }

    public function setData($aRules)
    {
        $this->_aData['data'] = @serialize($aRules);
    }

    public function getDataOne($sKey)
    {
        $aData = $this->getData();
        if (isset($aData[$sKey])) {
            return $aData[$sKey];
        }
        return null;
    }

    public function setDataOne($sKey, $mValue)
    {
        $aData = $this->getData();
        $aData[$sKey] = $mValue;
        $this->setData($aData);
    }

    public function getRelationTarget()
    {
        return $this->_getDataOne('_relation_entity');
    }
}