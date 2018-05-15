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
 * Объект сущности тега топика
 *
 * @package application.modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopicTag extends Entity
{
    /**
     * Возвращает ID тега
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getDataOne('topic_tag_id');
    }

    /**
     * Возвращает ID топика
     *
     * @return int|null
     */
    public function getTopicId()
    {
        return $this->_getDataOne('topic_id');
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
     * Возвращает ID блога
     *
     * @return int|null
     */
    public function getBlogId()
    {
        return $this->_getDataOne('blog_id');
    }

    /**
     * Возвращает текст тега
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->_getDataOne('topic_tag_text');
    }

    /**
     * Возвращает количество тегов
     *
     * @return int|null
     */
    public function getCount()
    {
        return $this->_getDataOne('count');
    }

    /**
     * Возвращает просчитанный размер тега для облака тегов
     *
     * @return int|null
     */
    public function getSize()
    {
        return $this->_getDataOne('size');
    }

    /**
     * Возвращает URL страницы тегов
     *
     * @return string
     */
    public function getUrl()
    {
        return Router::GetPath('tag') . urlencode($this->getText()) . '/';
    }

    /**
     * Устанавливает ID тега
     *
     * @param int $data
     */
    public function setId($data)
    {
        $this->_aData['topic_tag_id'] = $data;
    }

    /**
     * Устанавливает ID топика
     *
     * @param int $data
     */
    public function setTopicId($data)
    {
        $this->_aData['topic_id'] = $data;
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
     * Устанавливает ID блога
     *
     * @param int $data
     */
    public function setBlogId($data)
    {
        $this->_aData['blog_id'] = $data;
    }

    /**
     * Устанавливает текст тега
     *
     * @param string $data
     */
    public function setText($data)
    {
        $this->_aData['topic_tag_text'] = $data;
    }

    /**
     * Устанавливает просчитанный размер тега для облака тегов
     *
     * @param int $data
     */
    public function setSize($data)
    {
        $this->_aData['size'] = $data;
    }
}
