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
 * Экшен обработки подписок пользователей
 *
 * @package application.actions
 * @since 1.0
 */
class ActionSubscribe extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;

    /**
     * Инициализация
     *
     */
    public function Init()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Регистрация евентов
     *
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^unsubscribe$/i', '/^\w{32}$/i', 'EventUnsubscribe');
        $this->AddEvent('ajax-subscribe-toggle', 'EventAjaxSubscribeToggle');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */


    /**
     * Отписка от подписки
     */
    protected function EventUnsubscribe()
    {
        /**
         * Получаем подписку по ключу
         */
        if ($oSubscribe = $this->Subscribe_GetSubscribeByKey($this->getParam(0)) and $oSubscribe->getStatus() == 1) {
            /**
             * Отписываем пользователя
             */
            $oSubscribe->setStatus(0);
            $oSubscribe->setDateRemove(date("Y-m-d H:i:s"));
            $this->Subscribe_UpdateSubscribe($oSubscribe);

            $this->Message_AddNotice($this->Lang_Get('common.success.save'), null, true);
        }
        /**
         * Получаем URL для редиректа
         */
        if ((!$sUrl = $this->Subscribe_GetUrlTarget($oSubscribe->getTargetType(), $oSubscribe->getTargetId()))) {
            $sUrl = Router::GetPath('index');
        }
        Router::Location($sUrl);
    }

    /**
     * Изменение состояния подписки
     */
    protected function EventAjaxSubscribeToggle()
    {
        /**
         * Устанавливаем формат Ajax ответа
         */
        $this->Viewer_SetResponseAjax('json');
        /**
         * Получаем емайл подписки и проверяем его на валидность
         */
        $sMail = getRequestStr('mail');
        if ($this->oUserCurrent) {
            $sMail = $this->oUserCurrent->getMail();
        }
        if (!func_check($sMail, 'mail')) {
            $this->Message_AddError($this->Lang_Get('field.email.notices.error'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Получаем тип объекта подписки
         */
        $sTargetType = getRequestStr('target_type');
        if (!$this->Subscribe_IsAllowTargetType($sTargetType)) {
            return $this->EventErrorDebug();
        }
        $sTargetId = getRequestStr('target_id') ? getRequestStr('target_id') : null;
        $iValue = getRequest('value') ? 1 : 0;

        $oSubscribe = null;
        /**
         * Есть ли доступ к подписке гостям?
         */
        if (!$this->oUserCurrent and !$this->Subscribe_IsAllowTargetForGuest($sTargetType)) {
            $this->Message_AddError($this->Lang_Get('common.error.need_authorization'), $this->Lang_Get('common.error.error'));
            return;
        }
        /**
         * Проверка объекта подписки
         */
        if (!$this->Subscribe_CheckTarget($sTargetType, $sTargetId, $iValue)) {
            return $this->EventErrorDebug();
        }
        /**
         * Если подписка еще не существовала, то создаем её
         */
        if ($oSubscribe = $this->Subscribe_AddSubscribeSimple($sTargetType, $sTargetId, $sMail,
            $this->oUserCurrent ? $this->oUserCurrent->getId() : null)
        ) {
            $oSubscribe->setStatus($iValue);
            $this->Subscribe_UpdateSubscribe($oSubscribe);
            $this->Message_AddNotice($this->Lang_Get('common.success.save'), $this->Lang_Get('common.attention'));
            return;
        }
        return $this->EventErrorDebug();
    }
}