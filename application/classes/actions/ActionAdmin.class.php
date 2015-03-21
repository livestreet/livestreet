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
 * Экшен обработки УРЛа вида /admin/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionAdmin extends Action
{
    /**
     * Текущий пользователь
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'admin';

    /**
     * Инициализация
     *
     * @return string
     */
    public function Init()
    {
        /**
         * Если нет прав доступа - перекидываем на 404 страницу
         */
        if (!$this->User_IsAuthorization() or !$oUserCurrent = $this->User_GetUserCurrent() or !$oUserCurrent->isAdministrator()) {
            return parent::EventNotFound();
        }
        $this->SetDefaultEvent('index');

        $this->oUserCurrent = $oUserCurrent;
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
        $this->AddEvent('plugins', 'EventPlugins');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Отображение главной страницы админки
     */
    protected function EventIndex()
    {
        /**
         * Определяем доступность установки расширенной админ-панели
         */
        $aPluginsAll = func_list_plugins(true);
        if (in_array('admin', $aPluginsAll)) {
            $this->Viewer_Assign('availableAdminPlugin', true);
        }
    }

    /**
     * Страница со списком плагинов
     *
     */
    protected function EventPlugins()
    {
        $this->sMenuHeadItemSelect = 'plugins';
        /**
         * Получаем название плагина и действие
         */
        if ($sPlugin = getRequestStr('plugin', null, 'get') and $sAction = getRequestStr('action', null, 'get')) {
            return $this->SubmitManagePlugin($sPlugin, $sAction);
        }
        /**
         * Получаем список блогов
         */
        $aPlugins = $this->PluginManager_GetPluginsItems(array('order' => 'name'));
        /**
         * Загружаем переменные в шаблон
         */
        $this->Viewer_Assign('plugins', $aPlugins);
        $this->Viewer_AddHtmlTitle($this->Lang_Get('admin.plugins.title'));
        /**
         * Устанавливаем шаблон вывода
         */
        $this->SetTemplateAction('plugins');
    }

    /**
     * Активация\деактивация плагина
     *
     * @param string $sPlugin Имя плагина
     * @param string $sAction Действие
     */
    protected function SubmitManagePlugin($sPlugin, $sAction)
    {
        $this->Security_ValidateSendForm();
        if (!in_array($sAction, array('activate', 'deactivate', 'remove', 'apply_update'))) {
            $this->Message_AddError($this->Lang_Get('admin.plugins.notices.unknown_action'), $this->Lang_Get('common.error.error'),
                true);
            Router::Location(Router::GetPath('admin/plugins'));
        }
        $bResult = false;
        /**
         * Активируем\деактивируем плагин
         */
        if ($sAction == 'activate') {
            $bResult = $this->PluginManager_ActivatePlugin($sPlugin);
        } elseif ($sAction == 'deactivate') {
            $bResult = $this->PluginManager_DeactivatePlugin($sPlugin);
        } elseif ($sAction == 'remove') {
            $bResult = $this->PluginManager_RemovePlugin($sPlugin);
        } elseif ($sAction == 'apply_update') {
            $this->PluginManager_ApplyPluginUpdate($sPlugin);
            $bResult = true;
        }
        if ($bResult) {
            $this->Message_AddNotice($this->Lang_Get('admin.plugins.notices.action_ok'), $this->Lang_Get('common.attention'),
                true);
        } else {
            if (!($aMessages = $this->Message_GetErrorSession()) or !count($aMessages)) {
                $this->Message_AddErrorSingle($this->Lang_Get('common.error.system.base'), $this->Lang_Get('common.error.error'), true);
            }
        }
        /**
         * Возвращаем на страницу управления плагинами
         */
        Router::Location(Router::GetPath('admin') . 'plugins/');
    }

    /**
     * Выполняется при завершении работы экшена
     *
     */
    public function EventShutdown()
    {
        /**
         * Загружаем в шаблон необходимые переменные
         */
        $this->Viewer_Assign('sMenuHeadItemSelect', $this->sMenuHeadItemSelect);
    }
}