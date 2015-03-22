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
 * Модуль рассылок уведомлений пользователям
 *
 * @package application.modules.notify
 * @since 1.0
 */
class ModuleNotify extends Module
{
    /**
     * Статусы степени обработки заданий отложенной публикации в базе данных
     */
    const NOTIFY_TASK_STATUS_NULL = 1;
    /**
     * Объект локального вьювера для рендеринга сообщений
     *
     * @var ModuleViewer
     */
    protected $oViewerLocal = null;
    /**
     * Массив заданий на удаленную публикацию
     *
     * @var array
     */
    protected $aTask = array();
    /**
     * Объект маппера
     *
     * @var ModuleNotify_MapperNotify
     */
    protected $oMapper = null;

    /**
     * Префикс шаблонов
     *
     * @var string
     */
    protected $sPrefix = '';

    /**
     * Название директории с шаблономи
     *
     * @var string
     */
    protected $sDir = '';

    /**
     * Инициализация модуля
     * Создаём локальный экземпляр модуля Viewer
     * Момент довольно спорный, но позволяет избавить основной шаблон от мусора уведомлений
     *
     */
    public function Init()
    {
        $this->oViewerLocal = $this->Viewer_GetLocalViewer();
        $this->oMapper = Engine::GetMapper(__CLASS__);
        $this->sDir = Config::Get('module.notify.dir');
        $this->sPrefix = Config::Get('module.notify.prefix');
    }

    /**
     * Универсальный метод отправки уведомлений на email
     *
     * @param ModuleUser_EntityUser|string $oUserTo Кому отправляем (пользователь или email)
     * @param string $sTemplate Шаблон для отправки
     * @param string $sSubject Тема письма
     * @param array $aAssign Ассоциативный массив для загрузки переменных в шаблон письма
     * @param string|null $sPluginName Плагин из которого происходит отправка
     * @param bool $bForceSend Отправлять сразу, даже при опции module.notify.delayed = true
     */
    public function Send($oUserTo, $sTemplate, $sSubject, $aAssign = array(), $sPluginName = null, $bForceSend = false)
    {
        if ($oUserTo instanceof ModuleUser_EntityUser) {
            $sMail = $oUserTo->getMail();
            $sName = $oUserTo->getLogin();
        } else {
            $sMail = $oUserTo;
            $sName = '';
        }
        /**
         * Передаём в шаблон переменные
         */
        foreach ($aAssign as $k => $v) {
            $this->oViewerLocal->Assign($k, $v);
        }
        /**
         * Формируем шаблон
         */
        $sBody = $this->oViewerLocal->Fetch($this->GetTemplatePath($sTemplate, $sPluginName));
        /**
         * Если в конфигураторе указан отложенный метод отправки,
         * то добавляем задание в массив. В противном случае,
         * сразу отсылаем на email
         */
        if (Config::Get('module.notify.delayed') and !$bForceSend) {
            $oNotifyTask = Engine::GetEntity(
                'Notify_Task',
                array(
                    'user_mail'          => $sMail,
                    'user_login'         => $sName,
                    'notify_text'        => $sBody,
                    'notify_subject'     => $sSubject,
                    'date_created'       => date("Y-m-d H:i:s"),
                    'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
                )
            );
            if (Config::Get('module.notify.insert_single')) {
                $this->aTask[] = $oNotifyTask;
            } else {
                $this->oMapper->AddTask($oNotifyTask);
            }
        } else {
            /**
             * Отправляем мыло
             */
            $this->Mail_SetAdress($sMail, $sName);
            $this->Mail_SetSubject($sSubject);
            $this->Mail_SetBody($sBody);
            $this->Mail_setHTML();
            $this->Mail_Send();
        }
    }

    /**
     * При завершении работы модуля проверяем наличие
     * отложенных заданий в массиве и при необходимости
     * передаем их в меппер
     */
    public function Shutdown()
    {
        if (!empty($this->aTask) && Config::Get('module.notify.delayed')) {
            $this->oMapper->AddTaskArray($this->aTask);
            $this->aTask = array();
        }
    }

    /**
     * Получает массив заданий на публикацию из базы с указанным количественным ограничением (выборка FIFO)
     *
     * @param  int $iLimit Количество
     * @return array
     */
    public function GetTasksDelayed($iLimit = 10)
    {
        return ($aResult = $this->oMapper->GetTasks($iLimit))
            ? $aResult
            : array();
    }

    /**
     * Отправляет на e-mail
     *
     * @param ModuleNotify_EntityTask $oTask Объект задания на отправку
     */
    public function SendTask($oTask)
    {
        $this->Mail_SetAdress($oTask->getUserMail(), $oTask->getUserLogin());
        $this->Mail_SetSubject($oTask->getNotifySubject());
        $this->Mail_SetBody($oTask->getNotifyText());
        $this->Mail_setHTML();
        $this->Mail_Send();
    }

    /**
     * Удаляет отложенное Notify-задание из базы
     *
     * @param  ModuleNotify_EntityTask $oTask Объект задания на отправку
     * @return bool
     */
    public function DeleteTask($oTask)
    {
        return $this->oMapper->DeleteTask($oTask);
    }

    /**
     * Удаляет отложенные Notify-задания по списку идентификаторов
     *
     * @param  array $aArrayId Список ID заданий на отправку
     * @return bool
     */
    public function DeleteTaskByArrayId($aArrayId)
    {
        return $this->oMapper->DeleteTaskByArrayId($aArrayId);
    }

    /**
     * Возвращает путь к шаблону по переданному имени
     *
     * @param  string $sName Название шаблона
     * @param  string $sPluginName Название или класс плагина
     * @return string
     */
    public function GetTemplatePath($sName, $sPluginName = null)
    {
        $sName = $this->sPrefix ? $this->sPrefix . '.' . $sName : $sName;
        if ($sPluginName) {
            $sPluginName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui', $sPluginName, $aMatches)
                ? strtolower($aMatches[1])
                : strtolower($sPluginName);

            return Plugin::GetTemplatePath($sPluginName) . $this->sDir . '/' . $sName;
        } else {
            return $this->sDir . '/' . $sName;
        }
    }
}