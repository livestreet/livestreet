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
 * Объект сущности задания на отправку емайла
 *
 * @package application.modules.notify
 * @since 1.0
 */
class ModuleNotify_EntityTask extends Entity
{
    /**
     * Возвращает ID задания
     *
     * @return int|null
     */
    public function getTaskId()
    {
        return $this->_getDataOne('notify_task_id');
    }

    /**
     * Возвращает емайл
     *
     * @return string|null
     */
    public function getUserMail()
    {
        return $this->_getDataOne('user_mail');
    }

    /**
     * Возвращает логин пользователя
     *
     * @return string|null
     */
    public function getUserLogin()
    {
        return $this->_getDataOne('user_login');
    }

    /**
     * Возвращает текст сообщения
     *
     * @return string|null
     */
    public function getNotifyText()
    {
        return $this->_getDataOne('notify_text');
    }

    /**
     * Возвращает дату создания сообщения
     *
     * @return string|null
     */
    public function getDateCreated()
    {
        return $this->_getDataOne('date_created');
    }

    /**
     * Возвращает статус отправки
     *
     * @return int|null
     */
    public function getTaskStatus()
    {
        return $this->_getDataOne('notify_task_status');
    }

    /**
     * Возвращает тему сообщения
     *
     * @return string|null
     */
    public function getNotifySubject()
    {
        return $this->_getDataOne('notify_subject');
    }


    /**
     * Устанавливает ID задания
     *
     * @param int $data
     */
    public function setTaskId($data)
    {
        $this->_aData['notify_task_id'] = $data;
    }

    /**
     * Устанавливает емайл
     *
     * @param string $data
     */
    public function setUserMail($data)
    {
        $this->_aData['user_mail'] = $data;
    }

    /**
     * Устанавливает логин
     *
     * @param string $data
     */
    public function setUserLogin($data)
    {
        $this->_aData['user_login'] = $data;
    }

    /**
     * Устанавливает текст уведомления
     *
     * @param string $data
     */
    public function setNotifyText($data)
    {
        $this->_aData['notify_text'] = $data;
    }

    /**
     * Устанавливает дату создания задания
     *
     * @param string $data
     */
    public function setDateCreated($data)
    {
        $this->_aData['date_created'] = $data;
    }

    /**
     * Устанавливает статус задания
     *
     * @param int $data
     */
    public function setTaskStatus($data)
    {
        $this->_aData['notify_task_status'] = $data;
    }

    /**
     * Устанавливает тему сообщения
     *
     * @param string $data
     */
    public function setNotifySubject($data)
    {
        $this->_aData['notify_subject'] = $data;
    }
}