{**
 * Список пользователей с элементами управления / Пользователь
 *}

{$iUserId = $oUser->getId()}

<li class="user-list-small-item js-user-list-small-item {$sUserListSmallItemClasses}" data-user-id="{$iUserId}">
	{* Чекбокс *}
	{if $bUserListSmallSelectable}
		<input type="checkbox" class="js-user-list-small-checkbox" data-user-id="{$iUserId}" data-user-login="{$oUser->getLogin()}" /> 
	{/if}

	{* Пользователь *}
	{include 'user_item.tpl' oUser=$oUser}
	
	{* Действия *}
	{if $bUserListSmallShowActions}
		<ul class="user-list-small-item-actions js-user-list-small-actions">
			{if $bUserListItemShowRemove|default:true}
				{block 'user_list_small_item_actions'}{/block}
				<li class="icon-remove js-user-list-add-user-remove" title="{$aLang.common.remove}" data-user-id="{$iUserId}"></li>
			{/if}
		</ul>
	{/if}
</li>