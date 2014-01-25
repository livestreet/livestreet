{**
 * Кнопка Вступить / Покинуть блог
 *
 * @param object $oBlog        Блог
 * @param object $oUserCurrent Текущий пользователь
 * 
 * @scripts <framework>/js/livestreet/blog.js
 *}

{if $oUserCurrent && $oUserCurrent->getId() != $oBlog->getOwnerId() && $oBlog->getType() == 'open'}
	{include 'forms/fields/form.field.button.tpl'
			 sFieldAttributes = "data-blog-id=\"{$oBlog->getId()}\""
			 sFieldClasses    = 'js-blog-join'
			 sFieldText       = ($oBlog->getUserIsJoin()) ? $aLang.blog.join.leave : $aLang.blog.join.join
			 sFieldStyle      = ($oBlog->getUserIsJoin()) ? false : 'primary'}
{/if}