{**
 * Список пользователей с элементами управления / Пользователь
 * Расширяет основной шаблон с пользователем добавляя кнопку "Повторно отправить приглашение" в блоке "Пригласить пользователей в блог"
 *}

{extends 'components/user/user-list-small-item.tpl'}

{block 'components/user_list_small/user_list_small_item_actions'}
	<li class="icon-repeat js-blog-invite-user-repeat" title="{$aLang.blog.invite.repeat}" data-user-id="{$iUserId}"></li>

	{$smarty.block.parent}
{/block}