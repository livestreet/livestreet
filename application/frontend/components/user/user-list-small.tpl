{**
 * Список пользователей с элементами управления
 *
 * @param object  $users
 * @param string  $title
 * @param boolean $hideableEmptyAlert
 * @param boolean $show
 * @param boolean $selectable
 * @param array   $exclude
 * @param string  $itemTemplate
 * @param string  $classes
 *}

{$users = $smarty.local.users}
{$classes = $smarty.local.classes}

{* Заголовок *}
{if $smarty.local.title}
	<h3 class="user-list-small-title">{$smarty.local.title}</h3>
{/if}

{* Уведомление о пустом списке *}
{if ! $users || $smarty.local.hideableEmptyAlert}
	{component 'alert'
		text    = $aLang.common.empty
		mods    = 'empty'
		classes = 'js-user-list-small-empty'
		visible = ! $users}
{/if}

{* Список пользователей *}
{if $users || ! $smarty.local.show|default:true}
	<ul class="user-list-small js-user-list-small {$classes}" {if ! $smarty.local.show|default:true}style="display: none"{/if}>
		{foreach $users as $user}
			{$userContainer = $user}

			{if $user->getUser()}
				{$user = $user->getUser()}
			{/if}

			{if ! $smarty.local.exclude || ! in_array( $user->getId(), $smarty.local.exclude )}
				{block 'user_list_small_item'}
					{*include $itemTemplate|default:'./user-list-small-item.tpl' showRemove=! $excludeRemove || ! in_array( $user->getId(), $aUserListSmallExcludeRemove )*}
					{include './user-list-small-item.tpl' user=$user selectable=$smarty.local.selectable}
				{/block}
			{/if}
		{/foreach}
	</ul>
{/if}