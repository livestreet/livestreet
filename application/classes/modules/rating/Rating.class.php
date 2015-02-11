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
 * Модуль управления рейтингами и силой
 *
 * @package application.modules.rating
 * @since 1.0
 */
class ModuleRating extends Module
{

    /**
     * Инициализация модуля
     *
     */
    public function Init()
    {

    }

    /**
     * Расчет рейтинга при голосовании за комментарий
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя, который голосует
     * @param ModuleComment_EntityComment $oComment Объект комментария
     * @param int $iValue
     * @return int
     */
    public function VoteComment(ModuleUser_EntityUser $oUser, ModuleComment_EntityComment $oComment, $iValue)
    {
        /**
         * Устанавливаем рейтинг комментария
         */
        $oComment->setRating($oComment->getRating() + $iValue);
        /**
         * Меняем рейтинг автора коммента
         */
        $fDeltaUser = ($iValue < 0 ? -1 : 1) * Config::Get('module.rating.comment_multiplier');
        $oUserComment = $this->User_GetUserById($oComment->getUserId());
        $oUserComment->setRating($oUserComment->getRating() + $fDeltaUser);
        $this->User_Update($oUserComment);
        return $iValue;
    }

    /**
     * Расчет рейтинга и силы при гоосовании за топик
     *
     * @param ModuleUser_EntityUser $oUser Объект пользователя, который голосует
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @param int $iValue
     * @return int
     */
    public function VoteTopic(ModuleUser_EntityUser $oUser, ModuleTopic_EntityTopic $oTopic, $iValue)
    {
        /**
         * Устанавливаем рейтинг топика
         */
        $oTopic->setRating($oTopic->getRating() + $iValue);
        /**
         * Меняем рейтинг автора топика
         */
        $fDeltaUser = ($iValue < 0 ? -1 : 1) * Config::Get('module.rating.topic_multiplier');
        $oUserTopic = $this->User_GetUserById($oTopic->getUserId());
        $oUserTopic->setRating($oUserTopic->getRating() + $fDeltaUser);
        $this->User_Update($oUserTopic);
        return $iValue;
    }
}
