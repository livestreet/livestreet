{**
 * Кнопка Вступить / Покинуть блог
 *
 * @param object $blog        Блог
 * @param object $oUserCurrent Текущий пользователь
 *
 * @scripts <framework>/js/livestreet/blog.js
 *}

{if $oUserCurrent && $oUserCurrent->getId() != $blog->getOwnerId() && $blog->getType() == 'open'}
    {include 'components/button/button.tpl'
        attributes = "data-blog-id=\"{$blog->getId()}\""
        classes    = 'js-blog-join'
        text       = ($blog->getUserIsJoin()) ? $aLang.blog.join.leave : $aLang.blog.join.join
        mods       = ($blog->getUserIsJoin()) ? false : 'primary'}
{/if}