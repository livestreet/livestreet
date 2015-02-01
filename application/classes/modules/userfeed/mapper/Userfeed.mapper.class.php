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
 * Маппер для работы с БД
 *
 * @package application.modules.userfeed
 * @since 1.0
 */
class ModuleUserfeed_MapperUserfeed extends Mapper
{
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
        $sql = 'SELECT * FROM ' . Config::Get('db.table.userfeed_subscribe') . ' WHERE
                user_id = ?d AND subscribe_type = ?d AND target_id = ?d';
        if (!$this->oDb->select($sql, $iUserId, $iSubscribeType, $iTargetId)) {
            $sql = 'INSERT INTO ' . Config::Get('db.table.userfeed_subscribe') . ' SET
                    user_id = ?d, subscribe_type = ?d, target_id = ?d';
            $this->oDb->query($sql, $iUserId, $iSubscribeType, $iTargetId);
            return true;
        }
        return false;
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
        $sql = 'DELETE FROM ' . Config::Get('db.table.userfeed_subscribe') . ' WHERE
                user_id = ?d AND subscribe_type = ?d AND target_id = ?d';
        $res = $this->oDb->query($sql, $iUserId, $iSubscribeType, $iTargetId);
        return $this->IsSuccessful($res);
    }

    /**
     * Получить список подписок пользователя
     *
     * @param int $iUserId ID пользователя, для которого загружаются подписки
     * @return array
     */
    public function getUserSubscribes($iUserId)
    {
        $sql = 'SELECT subscribe_type, target_id FROM ' . Config::Get('db.table.userfeed_subscribe') . ' WHERE user_id = ?d';
        $aSubscribes = $this->oDb->select($sql, $iUserId);
        $aResult = array('blogs' => array(), 'users' => array());

        if (!count($aSubscribes)) {
            return $aResult;
        }

        foreach ($aSubscribes as $aSubscribe) {
            if ($aSubscribe['subscribe_type'] == ModuleUserfeed::SUBSCRIBE_TYPE_BLOG) {
                $aResult['blogs'][] = $aSubscribe['target_id'];
            } elseif ($aSubscribe['subscribe_type'] == ModuleUserfeed::SUBSCRIBE_TYPE_USER) {
                $aResult['users'][] = $aSubscribe['target_id'];
            }
        }
        return $aResult;
    }

    /**
     * Получить ленту топиков по подписке
     *
     * @param $aUserId array Список ID юзеров
     * @param $aBlogId array Список ID блогов
     * @param $aBlogIdClose array Список ID закрытых блогов пользователя блогов
     * @param $iCount
     * @param $iCurrPage
     * @param $iPerPage
     * @return array
     */
    public function ReadFeed($aUserId, $aBlogId, $aBlogIdClose, &$iCount, $iCurrPage, $iPerPage)
    {
        if (!is_array($aUserId)) {
            $aUserId = array($aUserId);
        }
        if (!is_array($aBlogId)) {
            $aBlogId = array($aBlogId);
        }
        if (!is_array($aBlogIdClose)) {
            $aBlogIdClose = array($aBlogIdClose);
        }
        $sql = "
							SELECT 		
								t.topic_id										
							FROM 
								" . Config::Get('db.table.topic') . " as t,
								" . Config::Get('db.table.blog') . " as b
							WHERE 
								t.topic_publish = 1 
								AND t.blog_id=b.blog_id 
								AND (
								        b.blog_type!='close'
								        { OR  t.blog_id IN (?a) }
								        { OR  t.blog_id2 IN (?a) }
								        { OR  t.blog_id3 IN (?a) }
								        { OR  t.blog_id4 IN (?a) }
								        { OR  t.blog_id5 IN (?a) }
								    )
								AND (
								        1=0
								        { OR t.blog_id IN (?a) }
								        { OR t.blog_id2 IN (?a) }
								        { OR t.blog_id3 IN (?a) }
								        { OR t.blog_id4 IN (?a) }
								        { OR t.blog_id5 IN (?a) }

								        { OR t.user_id IN (?a) }
								    )
                            ORDER BY t.topic_id DESC	
                            LIMIT ?d, ?d ";

        $aTopics = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql,
            count($aBlogIdClose) ? $aBlogIdClose : DBSIMPLE_SKIP,
            count($aBlogIdClose) ? $aBlogIdClose : DBSIMPLE_SKIP,
            count($aBlogIdClose) ? $aBlogIdClose : DBSIMPLE_SKIP,
            count($aBlogIdClose) ? $aBlogIdClose : DBSIMPLE_SKIP,
            count($aBlogIdClose) ? $aBlogIdClose : DBSIMPLE_SKIP,

            count($aBlogId) ? $aBlogId : DBSIMPLE_SKIP,
            count($aBlogId) ? $aBlogId : DBSIMPLE_SKIP,
            count($aBlogId) ? $aBlogId : DBSIMPLE_SKIP,
            count($aBlogId) ? $aBlogId : DBSIMPLE_SKIP,
            count($aBlogId) ? $aBlogId : DBSIMPLE_SKIP,

            count($aUserId) ? $aUserId : DBSIMPLE_SKIP,
            ($iCurrPage - 1) * $iPerPage, $iPerPage)
        ) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }
        return $aTopics;
    }
}