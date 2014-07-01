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
			 sAttributes = "data-blog-id=\"{$oBlog->getId()}\""
			 sClasses    = 'js-blog-join'
			 sText       = ($oBlog->getUserIsJoin()) ? $aLang.blog.join.leave : $aLang.blog.join.join
			 sMods      = ($oBlog->getUserIsJoin()) ? false : 'primary'}
{/if}