{**
 * Список пользователей с элементами управления / Пользователь
 *}

{$iUserId = $oUser->getId()}

<li class="user-list-small-item js-user-list-small-item {$sUserListSmallItemClasses} {block 'components/user_list_small/user_list_small_item_classes'}{/block}" {block 'components/user_list_small/user_list_small_item_attributes'}{/block} data-user-id="{$iUserId}">
	{* Чекбокс *}
	{if $bUserListSmallSelectable}
		<input type="checkbox" class="js-user-list-small-checkbox" data-user-id="{$iUserId}" data-user-login="{$oUser->getLogin()}" />
	{/if}

	{* Пользователь *}
	{include 'components/user_item/user_item.tpl' oUser=$oUser}

	{* Действия *}
	{if $bUserListSmallShowActions}
		<ul class="user-list-small-item-actions js-user-list-small-actions">
			{block 'components/user_list_small/user_list_small_item_actions'}
				{if $bUserListItemShowRemove|default:true}
					<li class="icon-remove js-user-list-add-user-remove" title="{$aLang.common.remove}" data-user-id="{$iUserId}"></li>
				{/if}
			{/block}
		</ul>
	{/if}
</li>