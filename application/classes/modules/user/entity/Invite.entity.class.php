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
 * Сущность инвайта(приглашения)
 *
 * @package application.modules.user
 * @since 1.0
 */
class ModuleUser_EntityInvite extends Entity
{
    /**
     * Возвращает ID инвайта
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getDataOne('invite_id');
    }

    /**
     * Возвращает код инвайта
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->_getDataOne('invite_code');
    }

    /**
     * Возвращает ID пользователя, который отправляет инвайт
     *
     * @return int|null
     */
    public function getUserFromId()
    {
        return $this->_getDataOne('user_from_id');
    }

    /**
     * Возвращает ID пользователя, которому отправляем инвайт
     *
     * @return int|null
     */
    public function getUserToId()
    {
        return $this->_getDataOne('user_to_id');
    }

    /**
     * Возвращает дату выдачи инвайта
     *
     * @return string|null
     */
    public function getDateAdd()
    {
        return $this->_getDataOne('invite_date_add');
    }

    /**
     * Возвращает дату использования инвайта
     *
     * @return string|null
     */
    public function getDateUsed()
    {
        return $this->_getDataOne('invite_date_used');
    }

    /**
     * Возвращает статус использованости инвайта
     *
     * @return int|null
     */
    public function getUsed()
    {
        return $this->_getDataOne('invite_used');
    }


    /**
     * Устанавливает ID инвайта
     *
     * @param int $data
     */
    public function setId($data)
    {
        $this->_aData['invite_id'] = $data;
    }

    /**
     * Устанавливает код инвайта
     *
     * @param string $data
     */
    public function setCode($data)
    {
        $this->_aData['invite_code'] = $data;
    }

    /**
     * Устанавливает ID пользователя, который отправляет инвайт
     *
     * @param int $data
     */
    public function setUserFromId($data)
    {
        $this->_aData['user_from_id'] = $data;
    }

    /**
     * Устанавливает ID пользователя, которому отправляем инвайт
     *
     * @param int $data
     */
    public function setUserToId($data)
    {
        $this->_aData['user_to_id'] = $data;
    }

    /**
     * Устанавливает дату выдачи инвайта
     *
     * @param string $data
     */
    public function setDateAdd($data)
    {
        $this->_aData['invite_date_add'] = $data;
    }

    /**
     * Устанавливает дату использования инвайта
     *
     * @param string $data
     */
    public function setDateUsed($data)
    {
        $this->_aData['invite_date_used'] = $data;
    }

    /**
     * Устанавливает статус использованости инвайта
     *
     * @param int $data
     */
    public function setUsed($data)
    {
        $this->_aData['invite_used'] = $data;
    }
}