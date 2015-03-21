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
 * Обрабатывает пользовательские ленты контента
 *
 * @package application.actions
 * @since 1.0
 */
class ActionUserfeed extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        /**
         * Доступ только у авторизованных пользователей
         */
        $this->oUserCurrent = $this->User_getUserCurrent();
        if (!$this->oUserCurrent) {
            parent::EventNotFound();
        }
        $this->Viewer_Assign('sMenuItemSelect', 'feed');
    }

    /**
     * Регистрация евентов
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^(page([1-9]\d{0,5}))?$/i', 'EventIndex');
        $this->AddEvent('subscribe', 'EventSubscribe');
        $this->AddEvent('ajaxadduser', 'EventAjaxAddUser');
        $this->AddEvent('unsubscribe', 'EventUnSubscribe');
    }

    /**
     * Выводит ленту контента(топики) для пользователя
     *
     */
    protected function EventIndex()
    {
        /**
         * Передан ли номер страницы
         */
        $iPage = $this->GetEventMatch(2) ? $this->GetEventMatch(2) : 1;

        $aResult = $this->Userfeed_read($this->oUserCurrent->getId(),$iPage,Config::Get('module.topic.per_page'));
        $aTopics = $aResult['collection'];

        // Вызов хуков
        $this->Hook_Run('topics_list_show', array('aTopics' => $aTopics));
        /**
         * Формируем постраничность
         */
        $aPaging = $this->Viewer_MakePaging($aResult['count'], $iPage, Config::Get('module.topic.per_page'),
            Config::Get('pagination.pages.count'), Router::GetPath('feed'));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('topics', $aTopics);
        $this->Viewer_Assign('paging', $aPaging);

        $this->SetTemplateAction('list');
    }
    
    /**
     * Подписка на контент блога или пользователя
     *
     */
    protected function EventSubscribe()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Проверяем наличие ID блога или пользователя
         */
        if (!getRequest('id')) {
            $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
        }
        $sType = getRequestStr('type');
        $iType = null;
        /**
         * Определяем тип подписки
         */
        switch ($sType) {
            case 'blogs':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
                /**
                 * Проверяем существование блога
                 */
                if (!($oBlog=$this->Blog_GetBlogById(getRequestStr('id'))) or !$this->ACL_IsAllowShowBlog($oBlog,$this->oUserCurrent)) {
                    $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                    return;
                }
                break;
            case 'users':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
                /**
                 * Проверяем существование пользователя
                 */
                if (!$this->User_GetUserById(getRequestStr('id'))) {
                    $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                    return;
                }
                if ($this->oUserCurrent->getId() == getRequestStr('id')) {
                    $this->Message_AddError($this->Lang_Get('user_list_add.notices.error_self'),
                        $this->Lang_Get('common.error.error'));
                    return;
                }
                break;
            default:
                $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                return;
        }
        /**
         * Подписываем
         */
        $this->Userfeed_subscribeUser($this->oUserCurrent->getId(), $iType, getRequestStr('id'));
        $this->Message_AddNotice($this->Lang_Get('common.success.save'), $this->Lang_Get('common.attention'));
    }

    /**
     * Подписка на пользвователя по логину
     *
     */
    protected function EventAjaxAddUser()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $aUsers = getRequest('users', null, 'post');
        /**
         * Валидация
         */
        if (!is_array($aUsers)) {
            return $this->EventErrorDebug();
        }
        /**
         * Если пользователь не авторизирован, возвращаем ошибку
         */
        if (!$this->User_IsAuthorization()) {
            $this->Message_AddErrorSingle($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }

        $aResult = array();
        /**
         * Обрабатываем добавление по каждому из переданных логинов
         */
        foreach ($aUsers as $sUser) {
            $sUser = trim($sUser);
            if ($sUser == '') {
                continue;
            }
            /**
             * Если пользователь не найден или неактивен, возвращаем ошибку
             */
            if ($oUser = $this->User_GetUserByLogin($sUser) and $oUser->getActivate() == 1) {
                $this->Userfeed_subscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_USER,
                    $oUser->getId());

                $oViewer = $this->Viewer_GetLocalViewer();
                $oViewer->Assign('user', $oUser, true);
                $oViewer->Assign('showActions', true, true);

                $aResult[] = array(
                    'bStateError'   => false,
                    'sMsgTitle'     => $this->Lang_Get('common.attention'),
                    'sMsg'          => $this->Lang_Get('common.success.add',
                        array('login' => htmlspecialchars($sUser))),
                    'user_id'       => $oUser->getId(),
                    'user_login'    => htmlspecialchars($sUser),
                    'html'          => $oViewer->Fetch("component@user-list-add.item")
                );
            } else {
                $aResult[] = array(
                    'bStateError' => true,
                    'sMsgTitle'   => $this->Lang_Get('common.error.error'),
                    'sMsg'        => $this->Lang_Get('user.notices.not_found',
                        array('login' => htmlspecialchars($sUser))),
                    'user_login'  => htmlspecialchars($sUser)
                );
            }
        }
        /**
         * Передаем во вьевер массив с результатами обработки по каждому пользователю
         */
        $this->Viewer_AssignAjax('users', $aResult);
    }

    /**
     * Отписка от блога или пользователя
     *
     */
    protected function EventUnsubscribe()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $sId = getRequestStr('id');

        $sType = getRequestStr('type');
        $iType = null;
        /**
         * Определяем от чего отписываемся
         */
        switch ($sType) {
            case 'blogs':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
                break;
            case 'users':
                $iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
                $sId = getRequestStr('user_id');
                break;
            default:
                $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
                return;
        }
        if (!$sId) {
            $this->Message_AddError($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Отписываем пользователя
         */
        $this->Userfeed_unsubscribeUser($this->oUserCurrent->getId(), $iType, $sId);
        $this->Message_AddNotice($this->Lang_Get('common.success.remove'), $this->Lang_Get('common.attention'));
    }

    /**
     * При завершении экшена загружаем в шаблон необходимые переменные
     *
     */
    public function EventShutdown()
    {
        /**
         * Подсчитываем новые топики
         */
        $iCountTopicsCollectiveNew = $this->Topic_GetCountTopicsCollectiveNew();
        $iCountTopicsPersonalNew = $this->Topic_GetCountTopicsPersonalNew();
        $iCountTopicsNew = $iCountTopicsCollectiveNew + $iCountTopicsPersonalNew;
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('iCountTopicsCollectiveNew', $iCountTopicsCollectiveNew);
        $this->Viewer_Assign('iCountTopicsPersonalNew', $iCountTopicsPersonalNew);
        $this->Viewer_Assign('iCountTopicsNew', $iCountTopicsNew);
    }
}