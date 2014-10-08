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
 * Экшен обработки УРЛа вида /comments/
 *
 * @package application.actions
 * @since 1.0
 */
class ActionComments extends Action
{
    /**
     * Текущий юзер
     *
     * @var ModuleUser_EntityUser|null
     */
    protected $oUserCurrent = null;
    /**
     * Главное меню
     *
     * @var string
     */
    protected $sMenuHeadItemSelect = 'blog';

    /**
     * Инициализация
     */
    public function Init()
    {
        $this->oUserCurrent = $this->User_GetUserCurrent();
    }

    /**
     * Регистрация евентов
     */
    protected function RegisterEvent()
    {
        $this->AddEventPreg('/^\d+$/i', 'EventShowComment');
    }


    /**********************************************************************************
     ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
     **********************************************************************************
     */

    /**
     * Обрабатывает ссылку на конкретный комментарий, определят к какому топику он относится и перенаправляет на него
     * Актуально при использовании постраничности комментариев
     */
    protected function EventShowComment()
    {
        $iCommentId = $this->sCurrentEvent;
        /**
         * Проверяем к чему относится комментарий
         */
        if (!($oComment = $this->Comment_GetCommentById($iCommentId))) {
            return parent::EventNotFound();
        }
        if ($oComment->getTargetType() != 'topic' or !($oTopic = $oComment->getTarget())) {
            return parent::EventNotFound();
        }
        /**
         * Определяем необходимую страницу для отображения комментария
         */
        if (!Config::Get('module.comment.use_nested') or !Config::Get('module.comment.nested_per_page')) {
            Router::Location($oTopic->getUrl() . '#comment' . $oComment->getId());
        }
        $iPage = $this->Comment_GetPageCommentByTargetId($oComment->getTargetId(), $oComment->getTargetType(),
            $oComment);
        if ($iPage == 1) {
            Router::Location($oTopic->getUrl() . '#comment' . $oComment->getId());
        } else {
            Router::Location($oTopic->getUrl() . "?cmtpage={$iPage}#comment" . $oComment->getId());
        }
        exit();
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
