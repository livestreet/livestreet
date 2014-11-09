{**
 * Список пользователей из определенной страны
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	{$aLang.user.users}:

	<span>
		{$oCountry->getName()|escape}

		{if $aPaging}
			({$aPaging.iCount})
		{/if}
	</span>
{/block}

{block 'layout_content'}
	{include 'components/user/user-list.tpl' users=$aUsersCountry pagination=$aPaging}
{/block}