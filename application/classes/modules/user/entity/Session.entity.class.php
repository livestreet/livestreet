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
 * Сущность сессии
 *
 * @package application.modules.user
 * @since 1.0
 */
class ModuleUser_EntitySession extends Entity
{
    /**
     * Возвращает ключ сессии
     *
     * @return string|null
     */
    public function getKey()
    {
        return $this->_getDataOne('session_key');
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
     * Возвращает IP создания сессии
     *
     * @return string|null
     */
    public function getIpCreate()
    {
        return $this->_getDataOne('session_ip_create');
    }

    /**
     * Возвращает последний IP сессии
     *
     * @return string|null
     */
    public function getIpLast()
    {
        return $this->_getDataOne('session_ip_last');
    }

    /**
     * Возвращает дату создания сессии
     *
     * @return string|null
     */
    public function getDateCreate()
    {
        return $this->_getDataOne('session_date_create');
    }

    /**
     * Возвращает последную дату сессии
     *
     * @return string|null
     */
    public function getDateLast()
    {
        return $this->_getDataOne('session_date_last');
    }

    /**
     * Возвращает дату закрытия сессии
     *
     * @return string|null
     */
    public function getDateClose()
    {
        return $this->_getDataOne('session_date_close');
    }

    /**
     * Возвращает дополнительные данные
     *
     * @return string|null
     */
    public function getExtra()
    {
        return $this->_getDataOne('session_extra');
    }

    /**
     * Проверяет факт активности сессии
     *
     * @return bool
     */
    public function isActive()
    {
        if ($this->getDateClose()) {
            return false;
        }
        return true;
    }

    /**
     * Возвращает параметр по имени
     *
     * @param $sName
     * @return null
     */
    public function getExtraParam($sName)
    {
        if ($sExtra = $this->getExtra() and $aData = @unserialize($sExtra)) {
            if (isset($aData[$sName])) {
                return $aData[$sName];
            }
        }
        return null;
    }

    /**
     * Устанавливает параметр по имени
     *
     * @param $sName
     * @param $mValue
     */
    public function setExtraParam($sName, $mValue)
    {
        if (!($sExtra = $this->getExtra() and $aData = @unserialize($sExtra))) {
            $aData = array();
        }
        $aData[$sName] = $mValue;
        $this->setExtra(serialize($aData));
    }


    /**
     * Устанавливает ключ сессии
     *
     * @param string $data
     */
    public function setKey($data)
    {
        $this->_aData['session_key'] = $data;
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
     * Устанавливает IP создания сессии
     *
     * @param string $data
     */
    public function setIpCreate($data)
    {
        $this->_aData['session_ip_create'] = $data;
    }

    /**
     * Устанавливает последний IP сессии
     *
     * @param string $data
     */
    public function setIpLast($data)
    {
        $this->_aData['session_ip_last'] = $data;
    }

    /**
     * Устанавливает дату создания сессии
     *
     * @param string $data
     */
    public function setDateCreate($data)
    {
        $this->_aData['session_date_create'] = $data;
    }

    /**
     * Устанавливает последную дату сессии
     *
     * @param string $data
     */
    public function setDateLast($data)
    {
        $this->_aData['session_date_last'] = $data;
    }

    /**
     * Устанавливает дату закрытия сессии
     *
     * @param string $data
     */
    public function setDateClose($data)
    {
        $this->_aData['session_date_close'] = $data;
    }

    /**
     * Устанавливает дополнительные данные
     *
     * @param string $data
     */
    public function setExtra($data)
    {
        $this->_aData['session_extra'] = $data;
    }
}