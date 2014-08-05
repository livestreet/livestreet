{**
 * Список всех пользователей
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	{$aLang.user.users}
{/block}

{block 'layout_content'}
	{include 'forms/search_forms/search_form.users.tpl'}
	{include 'components/alphanumeric/alphanumeric.tpl' aAlphaLetters=$aPrefixUser sType='users'}

	{* Сортировка *}
	{include 'components/sort/sort.ajax.tpl'
		sSortName     = 'sort-user-list'
		sSortSearchType     = 'users'
		aSortList     = [
			[ name => 'user_rating',        text => $aLang.sort.by_rating, order => 'asc'],
			[ name => 'user_login',         text => $aLang.sort.by_login ],
			[ name => 'user_date_register', text => $aLang.sort.by_date_registration ]
		]
	}

	<div class="js-search-ajax-container" data-type="users">
		{include 'components/user/user-list.tpl' aUsersList=$aUsers bUseMore=true}
	</div>
{/block}