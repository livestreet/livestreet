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
 * Сущность связи медиа данных с объектами
 *
 * @package application.modules.media
 * @since 2.0
 */
class ModuleMedia_EntityTarget extends EntityORM
{

    protected $aValidateRules = array();

    protected $aRelations = array(
        'media' => array(self::RELATION_TYPE_BELONGS_TO, 'ModuleMedia_EntityMedia', 'media_id'),
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
             * Удаляем превью
             */
            if ($this->getIsPreview() and $oMedia = $this->getMedia()) {
                $this->Media_RemoveFilePreview($oMedia, $this);
            }
        }
        return $bResult;
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

    public function getPreviewImageItemsWebPath()
    {
        $aPreviewItems = array();
        $sPathbase = $this->getDataOne('image_preview');
        $aSizes = $this->getDataOne('image_preview_sizes');
        if ($sPathbase and $aSizes) {
            foreach ($aSizes as $aSize) {
                $aPreviewItems[] = $this->Media_GetImageWebPath($sPathbase, $aSize);
            }
        }
        return $aPreviewItems;
    }
}