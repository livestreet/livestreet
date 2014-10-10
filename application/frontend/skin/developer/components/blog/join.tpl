{**
 * Кнопка Вступить / Покинуть блог
 *
 * @param object $oBlog        Блог
 * @param object $oUserCurrent Текущий пользователь
 *
 * @scripts <framework>/js/livestreet/blog.js
 *}

{if $oUserCurrent && $oUserCurrent->getId() != $oBlog->getOwnerId() && $oBlog->getType() == 'open'}
    {include 'components/button/button.tpl'
        attributes = "data-blog-id=\"{$oBlog->getId()}\""
        classes    = 'js-blog-join'
        text       = ($oBlog->getUserIsJoin()) ? $aLang.blog.join.leave : $aLang.blog.join.join
        mods       = ($oBlog->getUserIsJoin()) ? false : 'primary'}
{/if}