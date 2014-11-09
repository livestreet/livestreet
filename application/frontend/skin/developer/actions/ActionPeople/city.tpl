{**
 * Список пользователей из определенного города
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	{$aLang.user.users}:

	<span>
		{$oCity->getName()|escape}

		{if $aPaging}
			({$aPaging.iCount})
		{/if}
	</span>
{/block}

{block 'layout_content'}
	{include 'components/user/user-list.tpl' users=$aUsersCity pagination=$aPaging}
{/block}