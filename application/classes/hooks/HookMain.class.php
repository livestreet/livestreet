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
 * Регистрация основных хуков
 *
 * @package application.hooks
 * @since 1.0
 */
class HookMain extends Hook
{
    /**
     * Регистрируем хуки
     */
    public function RegisterHook()
    {
        $this->AddHook('init_action', 'InitAction', __CLASS__, 1000);
        $this->AddHook('start_action', 'StartAction', __CLASS__, 1000);
    }

    /**
     * Обработка хука инициализации экшенов
     * Может выполняться несколько раз, например, при использовании внутренних реврайтов
     */
    public function InitAction()
    {
        /**
         * Проверка на закрытый режим
         */
        $oUserCurrent = $this->User_GetUserCurrent();
        if (!$oUserCurrent and Config::Get('general.close') and !Router::CheckIsCurrentAction((array)Config::Get('general.close_exceptions'))) {
            Router::Action('auth/login');
        }
    }

    /**
     * Обработка запуска экшена
     * Выполняется всегда только один раз
     */
    public function StartAction()
    {
        $this->LoadDefaultJsVarAndLang();
        /**
         * Запуск обработки сборщика
         */
        $this->Ls_SenderRun();
    }

    /**
     * Загрузка необходимых переменных и текстовок в шаблон
     */
    public function LoadDefaultJsVarAndLang()
    {
        /**
         * Загружаем JS переменные
         */
        $this->Viewer_AssignJs(
            array(
                'recaptcha.site_key'    => Config::Get('module.validate.recaptcha.site_key'),
                'comment_max_tree'      => Config::Get('module.comment.max_tree'),
                'comment_show_form'     => Config::Get('module.comment.show_form'),
                'topic_max_blog_count'  => Config::Get('module.topic.max_blog_count'),
                'block_stream_show_tip' => Config::Get('block.stream.show_tip'),
                'poll_max_answers'      => Config::Get('module.poll.max_answers'),
            )
        );

        /**
         * Загрузка языковых текстовок
         */
        $this->Lang_AddLangJs(array(
            'comments.comments_declension',
            'comments.unsubscribe',
            'comments.subscribe',
            'comments.folding.unfold',
            'comments.folding.fold',
            'comments.folding.unfold_all',
            'comments.folding.fold_all',
            'poll.notices.error_answers_max',
            'blog.blog',
            'favourite.add',
            'favourite.remove',
            'field.geo.select_city',
            'field.geo.select_region',
            'blog.add.fields.type.note_open',
            'blog.add.fields.type.note_close',
            'common.success.add',
            'common.success.remove',
            'pagination.notices.first',
            'pagination.notices.last',
            'user.actions.unfollow',
            'user.actions.follow',
            'user.friends.status.added',
            'user.friends.status.notfriends',
            'user.friends.status.pending',
            'user.friends.status.rejected',
            'user.friends.status.sent',
            'user.friends.status.linked',
            'blog.blocks.navigator.blog',
            'user.settings.profile.notices.error_max_userfields',
            'common.remove_confirm',
            'more.empty',
            'validate.tags.count'
        ));
    }
}