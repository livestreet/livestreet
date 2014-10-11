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
 * Экшен обработки ленты активности
 *
 * @package application.actions
 * @since 1.0
 */
class ActionStream extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent;

    /**
     * Какое меню активно
     *
     * @var string
     */
    protected $sMenuItemSelect = 'user';

    /**
     * Инициализация
     */
    public function Init()
    {
        $this->oUserCurrent = $this->User_getUserCurrent();

        // Личная лента доступна только для авторизованных, гостям показываем общую ленту
        if ($this->oUserCurrent) {
            $this->SetDefaultEvent('personal');
        } else {
            $this->SetDefaultEvent('all');
        }

        $this->Viewer_Assign('sMenuHeadItemSelect', 'stream');

        /**
         * Загружаем в шаблон JS текстовки
         */
        $this->Lang_AddLangJs(array(
            'activity.notices.error_already_subscribed',
            'error'
        ));
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('personal', 'EventPersonal');
        $this->AddEvent('all', 'EventAll');

        $this->AddEvent('subscribe', 'EventSubscribe'); // TODO: возможно нужно удалить
        $this->AddEvent('ajaxadduser', 'EventAjaxAddUser');
        $this->AddEvent('ajaxremoveuser', 'EventAjaxRemoveUser');
        $this->AddEvent('switchEventType', 'EventSwitchEventType');

        $this->AddEvent('get_more_all', 'EventGetMoreAll');
        $this->AddEvent('get_more_personal', 'EventGetMore');
        $this->AddEvent('get_more_user', 'EventGetMoreUser');
    }

    /**
     * Персональная активность
     */
    protected function EventPersonal()
    {
        if (!$this->oUserCurrent) {
            return parent::EventNotFound();
        }

        $this->Viewer_AddBlock('right', 'activitySettings');
        $this->Viewer_AddBlock('right', 'activityUsers');

        $this->Viewer_Assign('activityEvents', $this->Stream_Read());
        $this->Viewer_Assign('activityEventsAllCount', $this->Stream_GetCountByReaderId($this->oUserCurrent->getId()));
    }

    /**
     * Общая активность
     */
    protected function EventAll()
    {
        $this->sMenuItemSelect = 'all';

        $this->Viewer_Assign('activityEvents', $this->Stream_ReadAll());
        $this->Viewer_Assign('activityEventsAllCount', $this->Stream_GetCountAll());
    }

    /**
     * Активаци/деактивация типа события
     */
    protected function EventSwitchEventType()
    {
        $this->Viewer_SetResponseAjax('json');

        if (!$this->oUserCurrent) {
            return parent::EventNotFound();
        }

        if (!getRequest('type')) {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
        }

        /**
         * Активируем/деактивируем тип
         */
        $this->Stream_switchUserEventType($this->oUserCurrent->getId(), getRequestStr('type'));
        $this->Message_AddNotice($this->Lang_Get('common.success.save'), $this->Lang_Get('attention'));
    }

    /**
     * Подгрузка событий (замена постраничности)
     */
    protected function EventGetMore()
    {
        if (!$this->oUserCurrent) {
            return parent::EventNotFound();
        }

        $this->GetMore(function ($lastId) {
            return $this->Stream_Read(null, $lastId);
        });
    }

    /**
     * Подгрузка событий для всего сайта
     */
    protected function EventGetMoreAll()
    {
        $this->GetMore(function ($lastId) {
            return $this->Stream_ReadAll(null, $lastId);
        });
    }

    /**
     * Подгрузка событий для пользователя
     */
    protected function EventGetMoreUser()
    {
        $this->GetMore(function ($lastId) {
            if (!($oUser = $this->User_GetUserById(getRequestStr('target_id')))) {
                return false;
            }

            return $this->Stream_ReadByUserId($oUser->getId(), null, $lastId);
        });
    }

    /**
     * Общий метод подгрузки событий
     *
     * @param callback $getEvents Метод возвращающий список событий
     */
    protected function GetMore($getEvents)
    {
        $this->Viewer_SetResponseAjax('json');

        // Необходимо передать последний просмотренный ID событий
        $iLastId = getRequestStr('last_id');

        if (!$iLastId) {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
            return;
        }

        // Получаем события
        $aEvents = $getEvents($iLastId);

        if ($aEvents === false) {
            return $this->EventErrorDebug();
        }

        $oViewer = $this->Viewer_GetLocalViewer();

        $oViewer->Assign('events', $aEvents, true);
        $oViewer->Assign('dateLast', getRequestStr('date_last'), true);

        if (count($aEvents)) {
            $this->Viewer_AssignAjax('last_id', end($aEvents)->getId(), true);
        }

        $this->Viewer_AssignAjax('count_loaded', count($aEvents));
        $this->Viewer_AssignAjax('html', $oViewer->Fetch('components/activity/event-list.tpl'));
    }

    /**
     * Подписка на пользователя по ID
     *
     */
    protected function EventSubscribe()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            return parent::EventNotFound();
        }
        /**
         * Проверяем существование пользователя
         */
        if (!$this->User_getUserById(getRequestStr('id'))) {
            $this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
        }
        if ($this->oUserCurrent->getId() == getRequestStr('id')) {
            $this->Message_AddError($this->Lang_Get('user_list_add.notices.error_self'), $this->Lang_Get('error'));
            return;
        }
        /**
         * Подписываем на пользователя
         */
        $this->Stream_subscribeUser($this->oUserCurrent->getId(), getRequestStr('id'));
        $this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
    }

    /**
     * Подписка на пользователя по логину
     */
    protected function EventAjaxAddUser()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        $aUsers = getRequest('aUserList', null, 'post');

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
            $this->Message_AddErrorSingle($this->Lang_Get('need_authorization'), $this->Lang_Get('error'));
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
                $this->Stream_subscribeUser($this->oUserCurrent->getId(), $oUser->getId());
                $oViewer = $this->Viewer_GetLocalViewer();
                $oViewer->Assign('oUser', $oUser);
                $oViewer->Assign('bUserListSmallShowActions', true);

                $aResult[] = array(
                    'bStateError'   => false,
                    'sMsgTitle'     => $this->Lang_Get('attention'),
                    'sMsg'          => $this->Lang_Get('common.success.add',
                        array('login' => htmlspecialchars($sUser))),
                    'sUserId'       => $oUser->getId(),
                    'sUserLogin'    => htmlspecialchars($sUser),
                    'sUserWebPath'  => $oUser->getUserWebPath(),
                    'sUserAvatar48' => $oUser->getProfileAvatarPath(48),
                    'sHtml'         => $oViewer->Fetch("components/user/user-list-small-item.tpl")
                );
            } else {
                $aResult[] = array(
                    'bStateError' => true,
                    'sMsgTitle'   => $this->Lang_Get('error'),
                    'sMsg'        => $this->Lang_Get('user.notices.not_found',
                        array('login' => htmlspecialchars($sUser))),
                    'sUserLogin'  => htmlspecialchars($sUser)
                );
            }
        }
        /**
         * Передаем во вьевер массив с результатами обработки по каждому пользователю
         */
        $this->Viewer_AssignAjax('aUserList', $aResult);
    }

    /**
     * Отписка от пользователя
     */
    protected function EventAjaxRemoveUser()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Пользователь авторизован?
         */
        if (!$this->oUserCurrent) {
            return $this->EventErrorDebug();
        }
        /**
         * Пользователь с таким ID существует?
         */
        if (!$this->User_GetUserById(getRequestStr('iUserId'))) {
            return $this->EventErrorDebug();
        }
        /**
         * Отписываем
         */
        $this->Stream_unsubscribeUser($this->oUserCurrent->getId(), getRequestStr('iUserId'));
        $this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
    }

    /**
     * Выполняется при завершении работы экшена
     */
    public function EventShutdown()
    {
        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuItemSelect', $this->sMenuItemSelect);
    }
}
