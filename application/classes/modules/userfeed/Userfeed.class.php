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
 * Модуль пользовательских лент контента (топиков)
 *
 * @package application.modules.userfeed
 * @since 1.0
 */
class ModuleUserfeed extends Module
{
    /**
     * Подписки на топики по блогу
     */
    const SUBSCRIBE_TYPE_BLOG = 1;
    /**
     * Подписки на топики по юзеру
     */
    const SUBSCRIBE_TYPE_USER = 2;
    /**
     * Объект маппера
     *
     * @var ModuleUserfeed_MapperUserfeed|null
     */
    protected $oMapper = null;
    /**
     * Объект текущего пользователя
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация модуля
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Подписать пользователя
     *
     * @param int $iUserId ID подписываемого пользователя
     * @param int $iSubscribeType Тип подписки (см. константы класса)
     * @param int $iTargetId ID цели подписки
     * @return bool
     */
    public function subscribeUser($iUserId, $iSubscribeType, $iTargetId)
    {
        return $this->oMapper->subscribeUser($iUserId, $iSubscribeType, $iTargetId);
    }

    /**
     * Отписать пользователя
     *
     * @param int $iUserId ID подписываемого пользователя
     * @param int $iSubscribeType Тип подписки (см. константы класса)
     * @param int $iTargetId ID цели подписки
     * @return bool
     */
    public function unsubscribeUser($iUserId, $iSubscribeType, $iTargetId)
    {
        return $this->oMapper->unsubscribeUser($iUserId, $iSubscribeType, $iTargetId);
    }

    /**
     * Получить ленту топиков по подписке
     *
     * @param $iUserId  ID пользователя, для которого получаем ленту
     * @param $iCurrPage
     * @param null $iPerPage
     * @return array
     */
    public function read($iUserId, $iCurrPage, $iPerPage = null)
    {
        if (!is_null($iPerPage)) {
            $iPerPage = Config::Get('module.userfeed.count_default');
        }
        $aSubscribes = $this->oMapper->getUserSubscribes($iUserId);
        /**
         * Добавляем в выдачу закрытые блоги
         */
        $aOpenBlogs = array();
        if ($this->oUserCurrent) {
            if ($aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent)) {
                $aOpenBlogs = array_intersect($aOpenBlogs, $aSubscribes['blogs']);
            }
        }
        $aTopicsIds = $this->oMapper->ReadFeed($aSubscribes['users'], $aSubscribes['blogs'], $aOpenBlogs, $iCount,
            $iCurrPage,
            $iPerPage);
        return array(
            'collection' => $this->Topic_GetTopicsAdditionalData($aTopicsIds),
            'count'      => $iCount
        );
    }

    /**
     * Получить список подписок пользователя
     *
     * @param int $iUserId ID пользователя, для которого загружаются подписки
     * @return array
     */
    public function getUserSubscribes($iUserId)
    {
        $aUserSubscribes = $this->oMapper->getUserSubscribes($iUserId);
        $aResult = array('blogs' => array(), 'users' => array());
        if (count($aUserSubscribes['blogs'])) {
            $aBlogs = $this->Blog_getBlogsByArrayId($aUserSubscribes['blogs']);
            foreach ($aBlogs as $oBlog) {
                $aResult['blogs'][$oBlog->getId()] = $oBlog;
            }
        }
        if (count($aUserSubscribes['users'])) {
            $aUsers = $this->User_getUsersByArrayId($aUserSubscribes['users']);
            foreach ($aUsers as $oUser) {
                $aResult['users'][$oUser->getId()] = $oUser;
            }
        }

        return $aResult;
    }
}