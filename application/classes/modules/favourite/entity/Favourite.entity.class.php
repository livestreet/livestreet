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
 * Объект сущности избрнного
 *
 * @package application.modules.favourite
 * @since 1.0
 */
class ModuleFavourite_EntityFavourite extends Entity
{
    /**
     * Возвращает ID владельца
     *
     * @return int|null
     */
    public function getTargetId()
    {
        return $this->_getDataOne('target_id');
    }

    /**
     * Возвращает ID пользователя
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->_getDataOne('user_id');
    }

    /**
     * Возвращает флаг публикации владельца
     *
     * @return int|null
     */
    public function getTargetPublish()
    {
        return $this->_getDataOne('target_publish');
    }

    /**
     * Возвращает тип владельца
     *
     * @return string|null
     */
    public function getTargetType()
    {
        return $this->_getDataOne('target_type');
    }

    /**
     * Возващает список тегов
     *
     * @return array
     */
    public function getTagsArray()
    {
        if ($this->getTags()) {
            return explode(',', $this->getTags());
        }
        return array();
    }

    /**
     * Устанавливает ID владельца
     *
     * @param int $data
     */
    public function setTargetId($data)
    {
        $this->_aData['target_id'] = $data;
    }

    /**
     * Устанавливает ID пользователя
     *
     * @param int $data
     */
    public function setUserId($data)
    {
        $this->_aData['user_id'] = $data;
    }

    /**
     * Устанавливает статус публикации для владельца
     *
     * @param int $data
     */
    public function setTargetPublish($data)
    {
        $this->_aData['target_publish'] = $data;
    }

    /**
     * Устанавливает тип владельца
     *
     * @param string $data
     */
    public function setTargetType($data)
    {
        $this->_aData['target_type'] = $data;
    }
}