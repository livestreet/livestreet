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
 * Регистрация хука для вывода ссылки копирайта
 *
 * @package application.hooks
 * @since 1.0
 */
class HookCopyright extends Hook
{
    /**
     * Регистрируем хуки
     */
    public function RegisterHook()
    {
        $this->AddHook('template_copyright', 'CopyrightLink', __CLASS__, -100);
    }

    /**
     * Обработка хука копирайта
     *
     * @return string
     */
    public function CopyrightLink()
    {
        /**
         * Выводим везде, кроме страницы списка блогов и списка всех комментов
         */
        return '&copy; Powered by <a href="http://livestreetcms.org">LiveStreet CMS</a>';
    }
}