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
 * ACL(Access Control List)
 * Модуль для разруливания ограничений по карме/рейтингу юзера
 *
 * @package application.modules.acl
 * @since 1.0
 */
class ModuleACL extends Module
{
    /**
     * Коды механизма удаления блога
     */
    const CAN_DELETE_BLOG_EMPTY_ONLY = 1;
    const CAN_DELETE_BLOG_WITH_TOPICS = 2;

    /**
     * Инициализация модуля
     *
     */
    public function Init()
    {

    }

    /**
     * Проверяет может ли пользователь создавать блоги
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanCreateBlog($oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_blog', array(
            'callback' => function ($oUser, $aParams) use ($that) {
                if (!$oUser) {
                    return false;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                /**
                 * Проверяем хватает ли рейтинга юзеру чтоб создать блог
                 */
                if ($oUser->getRating() < Config::Get('acl.create.blog.rating')) {
                    return $that->Lang_Get('blog.add.alerts.acl');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь создавать топики
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param ModuleTopic_EntityTopicType $oTopicType Объект типа топика
     * @return bool
     */
    public function CanAddTopic($oUser, $oTopicType)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_topic', array(
            'callback' => function ($oUser, $aParams) use ($that) {
                if (!$oUser) {
                    return false;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                /**
                 * Проверяем хватает ли рейтинга юзеру чтоб создать топик
                 */
                if ($oUser->getRating() <= Config::Get('acl.create.topic.limit_rating')) {
                    return $that->Lang_Get('topic.add.notices.rating_limit');
                }
                /**
                 * Проверяем лимит по времени
                 */
                if (!$that->CanPostTopicTime($oUser)) {
                    return $that->Lang_Get('topic.add.notices.time_limit');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь создавать комментарии
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @param  ModuleTopic_EntityTopic|null $oTopic Топик
     * @return bool
     */
    public function CanPostComment($oUser, $oTopic = null)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_topic_comment', array(
            'callback' => function ($oUser, $aParams) use ($that, $oTopic) {
                if (!$oUser) {
                    return false;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                /**
                 * Проверяем на закрытый блог
                 */
                if ($oTopic and !$that->IsAllowShowBlog($oTopic->getBlog(), $oUser)) {
                    return $that->Lang_Get('topic.comments.notices.acl');
                }
                /**
                 * Ограничение на рейтинг
                 */
                if ($oUser->getRating() < Config::Get('acl.create.comment.rating')) {
                    return $that->Lang_Get('topic.comments.notices.acl');
                }
                /**
                 * Ограничение по времени
                 */
                if (Config::Get('acl.create.comment.limit_time') > 0 and $oUser->getDateCommentLast()) {
                    $sDateCommentLast = strtotime($oUser->getDateCommentLast());
                    if ($oUser->getRating() < Config::Get('acl.create.comment.limit_time_rating') and ((time() - $sDateCommentLast) < Config::Get('acl.create.comment.limit_time'))) {
                        return $that->Lang_Get('topic.comments.notices.limit');
                    }
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь создавать топик по времени
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanPostTopicTime($oUser)
    {
        // Для администраторов ограничение по времени не действует
        if ($oUser->isAdministrator()
            or Config::Get('acl.create.topic.limit_time') == 0
            or $oUser->getRating() >= Config::Get('acl.create.topic.limit_time_rating')
        ) {
            return true;
        }

        /**
         * Проверяем, если топик опубликованный меньше чем acl.create.topic.limit_time секунд назад
         */
        $aTopics = $this->Topic_GetLastTopicsByUserId($oUser->getId(), Config::Get('acl.create.topic.limit_time'));
        if (isset($aTopics['count']) and $aTopics['count'] > 0) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет возможность отправки личного сообщения
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanAddTalk($oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_talk', array(
            'callback' => function ($oUser, $aParams) use ($that) {
                if (!$oUser) {
                    return false;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                if (!$that->CanSendTalkTime($oUser)) {
                    return $that->Lang_Get('talk.notices.time_limit');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь отправить инбокс по времени
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanSendTalkTime($oUser)
    {
        // Для администраторов ограничение по времени не действует
        if ($oUser->isAdministrator()
            or Config::Get('acl.create.talk.limit_time') == 0
            or $oUser->getRating() >= Config::Get('acl.create.talk.limit_time_rating')
        ) {
            return true;
        }

        /**
         * Проверяем, если топик опубликованный меньше чем acl.create.topic.limit_time секунд назад
         */
        $aTalks = $this->Talk_GetLastTalksByUserId($oUser->getId(), Config::Get('acl.create.talk.limit_time'));
        if (isset($aTalks['count']) and $aTalks['count'] > 0) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет может ли пользователь создавать комментарии к личным сообщениям
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanPostTalkComment($oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_talk_comment', array(
            'callback' => function ($oUser, $aParams) use ($that) {
                if (!$oUser) {
                    return false;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                $aTalkComments = $that->Comment_GetCommentsByUserId($oUser->getId(), 'talk', 1, 1);
                /**
                 * Если комментариев не было
                 */
                if (!is_array($aTalkComments) or $aTalkComments['count'] == 0) {
                    return true;
                }
                /**
                 * Достаем последний комментарий
                 */
                $oComment = array_shift($aTalkComments['collection']);
                $sDate = strtotime($oComment->getDate());

                if ($sDate and ((time() - $sDate) < Config::Get('acl.create.talk_comment.limit_time'))) {
                    return $that->Lang_Get('talk.add.notices.time_limit');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь голосовать за конкретный комментарий
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param ModuleComment_EntityComment $oComment Комментарий
     * @return bool
     */
    public function CanVoteComment($oUser, $oComment)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'vote_comment', array(
            'callback' => function ($oUser, $aParams) use ($that, $oComment) {
                if (!$oUser) {
                    return false;
                }
                /**
                 * Голосует автор комментария?
                 */
                if ($oComment->getUserId() == $oUser->getId()) {
                    return $that->Lang_Get('vote.notices.error_self');
                }
                /**
                 * Пользователь уже голосовал?
                 */
                if ($oTopicCommentVote = $that->Vote_GetVote($oComment->getId(), 'comment', $oUser->getId())) {
                    return $that->Lang_Get('vote.notices.error_already_voted');
                }
                /**
                 * Ограничение по рейтингу
                 */
                if ($oUser->getRating() < Config::Get('acl.vote.comment.rating')) {
                    return $that->Lang_Get('vote.notices.error_acl');
                }
                /**
                 * Время голосования истекло?
                 */
                if (strtotime($oComment->getDate()) <= time() - Config::Get('acl.vote.comment.limit_time')) {
                    return $that->Lang_Get('vote.notices.error_time');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет может ли пользователь голосовать за конкретный топик
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param ModuleTopic_EntityTopic $oTopic Топик
     * @param int $iValue Направление голосования
     * @return bool
     */
    public function CanVoteTopic($oUser, $oTopic, $iValue)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'vote_topic', array(
            'callback' => function ($oUser, $aParams) use ($that, $oTopic, $iValue) {
                if (!$oUser) {
                    return false;
                }
                /**
                 * Голосует автор топика?
                 */
                if ($oTopic->getUserId() == $oUser->getId()) {
                    return $that->Lang_Get('vote.notices.error_self');
                }
                /**
                 * Пользователь уже голосовал?
                 */
                if ($oTopicVote = $that->Vote_GetVote($oTopic->getId(), 'topic', $oUser->getId())) {
                    return $that->Lang_Get('vote.notices.error_already_voted');
                }
                /**
                 * Время голосования истекло?
                 */
                if (strtotime($oTopic->getDatePublish()) <= time() - Config::Get('acl.vote.topic.limit_time')) {
                    return $that->Lang_Get('vote.notices.error_time');
                }
                /**
                 * Ограничение по рейтингу
                 */
                if ($iValue != 0 and $oUser->getRating() < Config::Get('acl.vote.topic.rating')) {
                    return $that->Lang_Get('vote.notices.error_acl');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет можно ли юзеру слать инвайты
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanSendInvite($oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_invite', array(
            'callback' => function ($oUser, $aParams) use ($that) {
                if (!$oUser) {
                    return false;
                }
                if (!Config::Get('general.reg.invite')) {
                    // разрешаем приглашения всем, когда сайт открыт
                    return true;
                }
                if ($oUser->isAdministrator()) {
                    return true;
                }
                if ($that->Invite_GetCountInviteAvailable($oUser) == 0) {
                    return $that->Lang_Get('user.settings.invites.available_no');
                }
                return true;
            }
        ));
    }

    /**
     * Проверяет можно или нет юзеру постить в данный блог
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowBlog($oBlog, $oUser)
    {
        if (!$oBlog || !$oUser) {
            return false;
        }
        if ($oUser->isAdministrator()) {
            return true;
        }
        if ($oBlog->getOwnerId() == $oUser->getId()) {
            return true;
        }
        if ($oBlog->getType() == 'close') {
            /**
             * Для закрытых блогов проверяем среди подписчиков
             */
            if ($oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId())) {
                if ($oUser->getRating() >= $oBlog->getLimitRatingTopic() or $oBlogUser->getIsAdministrator() or $oBlogUser->getIsModerator()) {
                    return true;
                }
            }
        } else {
            /**
             * Иначе смотрим ограничение на рейтинг
             */
            if ($oUser->getRating() >= $oBlog->getLimitRatingTopic()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Проверяет можно или нет юзеру просматривать блог
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowShowBlog($oBlog, $oUser)
    {
        if ($oBlog->getType() != 'close') {
            return true;
        }
        if ($oUser->isAdministrator()) {
            return true;
        }
        if ($oBlog->getOwnerId() == $oUser->getId()) {
            return true;
        }
        if ($oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),
                $oUser->getId()) and $oBlogUser->getUserRole() > ModuleBlog::BLOG_USER_ROLE_GUEST
        ) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет можно или нет пользователю редактировать данный топик
     *
     * @param  ModuleTopic_EntityTopic $oTopic Топик
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowEditTopic($oTopic, $oUser)
    {
        /**
         * Разрешаем если это админ сайта или автор топика
         */
        if ($oTopic->getUserId() == $oUser->getId() or $oUser->isAdministrator()) {
            return true;
        }
        /**
         * Если автор(смотритель) блога
         */
        if ($oTopic->getBlog()->getOwnerId() == $oUser->getId()) {
            return true;
        }
        /**
         * Если модер или админ блога
         */
        if ($this->User_GetUserCurrent() and $this->User_GetUserCurrent()->getId() == $oUser->getId()) {
            /**
             * Для авторизованного пользователя данный код будет работать быстрее
             */
            if ($oTopic->getBlog()->getUserIsAdministrator() or $oTopic->getBlog()->getUserIsModerator()) {
                return true;
            }
        } else {
            $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oTopic->getBlogId(), $oUser->getId());
            if ($oBlogUser and ($oBlogUser->getIsModerator() or $oBlogUser->getIsAdministrator())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверка на редактирование комментария
     *
     * @param ModuleComment_EntityComment $oComment
     * @param ModuleUser_EntityUser $oUser
     *
     * @return bool
     */
    public function IsAllowEditComment($oComment, $oUser)
    {
        if (!$oUser) {
            return false;
        }
        if (!in_array($oComment->getTargetType(), (array)Config::Get('module.comment.edit_target_allow'))) {
            return false;
        }
        if ($oUser->isAdministrator()) {
            return true;
        }
        if ($oComment->getUserId() == $oUser->getId() and $oUser->getRating() >= Config::Get('acl.update.comment.rating')) {
            /**
             * Проверяем на лимит времени
             */
            if (!Config::Get('acl.update.comment.limit_time') or (time() - strtotime($oComment->getDate()) <= Config::Get('acl.update.comment.limit_time'))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Проверка на возможность добавления комментария в избранное
     *
     * @param $oComment
     * @param $oUser
     *
     * @return bool
     */
    public function IsAllowFavouriteComment($oComment, $oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'create_comment_favourite', array(
            'callback' => function ($oUser, $aParams) use ($that, $oComment) {
                if (!$oUser) {
                    return false;
                }
                if (!in_array($oComment->getTargetType(), array('topic'))) {
                    return false;
                }
                if (!$oTarget = $oComment->getTarget()) {
                    return false;
                }
                if ($oComment->getTargetType() == 'topic') {
                    /**
                     * Проверяем права на просмотр топика
                     */
                    if (!$that->IsAllowShowTopic($oTarget, $oUser)) {
                        return false;
                    }
                }
                return true;
            }
        ));
    }

    /**
     * Проверка на удаление комментария
     *
     * @param ModuleComment_EntityComment $oComment
     * @param ModuleUser_EntityUser $oUser
     *
     * @return bool
     */
    public function IsAllowDeleteComment($oComment, $oUser)
    {
        /**
         * Разрешаем если это админ сайта
         */
        if ($oUser and $oUser->isAdministrator()) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет можно или нет пользователю удалять данный топик
     *
     * @param ModuleTopic_EntityTopic $oTopic Топик
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowDeleteTopic($oTopic, $oUser)
    {
        $that = $this; // fix for PHP < 5.4
        return $this->Rbac_IsAllowUser($oUser, 'remove_topic', array(
            'callback' => function ($oUser, $aParams) use ($that, $oTopic) {
                if (!$oUser) {
                    return false;
                }
                /**
                 * Разрешаем если это админ сайта или автор топика
                 */
                if ($oTopic->getUserId() == $oUser->getId() or $oUser->isAdministrator()) {
                    return true;
                }
                /**
                 * Если автор(смотритель) блога
                 */
                if ($oTopic->getBlog()->getOwnerId() == $oUser->getId()) {
                    return true;
                }
                /**
                 * Если модер или админ блога
                 */
                if ($that->User_GetUserCurrent() and $that->User_GetUserCurrent()->getId() == $oUser->getId()) {
                    /**
                     * Для авторизованного пользователя данный код будет работать быстрее
                     */
                    if ($oTopic->getBlog()->getUserIsAdministrator() or $oTopic->getBlog()->getUserIsModerator()) {
                        return true;
                    }
                } else {
                    $oBlogUser = $that->Blog_GetBlogUserByBlogIdAndUserId($oTopic->getBlogId(), $oUser->getId());
                    if ($oBlogUser and ($oBlogUser->getIsModerator() or $oBlogUser->getIsAdministrator())) {
                        return true;
                    }
                }
                return false;
            }
        ));
    }

    /**
     * Проверка на возможность просмотра топика
     *
     * @param $oTopic
     * @param $oUser
     *
     * @return bool
     */
    public function IsAllowShowTopic($oTopic, $oUser)
    {
        if (!$oTopic) {
            return false;
        }
        /**
         * Проверяем права на просмотр топика
         */
        if ((!$oTopic->getPublish() or $oTopic->getDatePublish() > date('Y-m-d H:i:s'))
            and (!$oUser or ($oUser->getId() != $oTopic->getUserId() and !$oUser->isAdministrator()))) {
            return false;
        }
        /**
         * Определяем права на отображение записи из закрытого блога
         */
        if (!$this->IsAllowShowBlog($oTopic->getBlog(), $oUser)) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет можно или нет пользователю удалять данный блог
     *
     * @param ModuleBlog_EntityBlog $oBlog Блог
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowDeleteBlog($oBlog, $oUser)
    {
        /**
         * Разрешаем если это админ сайта или автор блога
         */
        if ($oUser->isAdministrator()) {
            return self::CAN_DELETE_BLOG_WITH_TOPICS;
        }
        /**
         * Разрешаем удалять администраторам блога и автору, но только пустой
         */
        if ($oBlog->getOwnerId() == $oUser->getId()) {
            return self::CAN_DELETE_BLOG_EMPTY_ONLY;
        }

        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId());
        if ($oBlogUser and $oBlogUser->getIsAdministrator()) {
            return self::CAN_DELETE_BLOG_EMPTY_ONLY;
        }
        return false;
    }

    /**
     * Проверяет может ли пользователь удалить комментарий
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function CanDeleteComment($oUser)
    {
        if (!$oUser || !$oUser->isAdministrator()) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет может ли пользователь публиковать на главной
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowTopicPublishIndex(ModuleUser_EntityUser $oUser)
    {
        if ($oUser->isAdministrator()) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет может ли пользователь блокировать топик на главной
     *
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowTopicSkipIndex(ModuleUser_EntityUser $oUser)
    {
        if ($oUser->isAdministrator()) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет можно или нет пользователю редактировать данный блог
     *
     * @param  ModuleBlog_EntityBlog $oBlog Блог
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowEditBlog($oBlog, $oUser)
    {
        if ($oUser->isAdministrator()) {
            return true;
        }
        /**
         * Разрешаем если это создатель блога
         */
        if ($oBlog->getOwnerId() == $oUser->getId()) {
            return true;
        }
        /**
         * Явлется ли авторизованный пользователь администратором блога
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId());

        if ($oBlogUser && $oBlogUser->getIsAdministrator()) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет можно или нет пользователю управлять пользователями блога
     *
     * @param  ModuleBlog_EntityBlog $oBlog Блог
     * @param  ModuleUser_EntityUser $oUser Пользователь
     * @return bool
     */
    public function IsAllowAdminBlog($oBlog, $oUser)
    {
        if ($oUser->isAdministrator()) {
            return true;
        }
        /**
         * Разрешаем если это создатель блога
         */
        if ($oBlog->getOwnerId() == $oUser->getId()) {
            return true;
        }
        /**
         * Явлется ли авторизованный пользователь администратором блога
         */
        $oBlogUser = $this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(), $oUser->getId());
        if ($oBlogUser && $oBlogUser->getIsAdministrator()) {
            return true;
        }
        return false;
    }

    /**
     * Проверка на ограничение по времени на постинг на стене
     *
     * @param ModuleUser_EntityUser $oUser Пользователь
     * @param ModuleWall_EntityWall $oWall Объект сообщения на стене
     * @return bool
     */
    public function CanAddWallTime($oUser, $oWall)
    {
        /**
         * Для администраторов ограничение по времени не действует
         */
        if ($oUser->isAdministrator()
            or Config::Get('acl.create.wall.limit_time') == 0
            or $oUser->getRating() >= Config::Get('acl.create.wall.limit_time_rating')
        ) {
            return true;
        }
        if ($oWall->getUserId() == $oWall->getWallUserId()) {
            return true;
        }
        /**
         * Получаем последнее сообщение
         */
        $aWall = $this->Wall_GetWall(array('user_id' => $oWall->getUserId()), array('id' => 'desc'), 1, 1, array());
        /**
         * Если сообщений нет
         */
        if ($aWall['count'] == 0) {
            return true;
        }

        $oWallLast = array_shift($aWall['collection']);
        $sDate = strtotime($oWallLast->getDateAdd());
        if ($sDate and ((time() - $sDate) < Config::Get('acl.create.wall.limit_time'))) {
            return false;
        }
        return true;
    }
}