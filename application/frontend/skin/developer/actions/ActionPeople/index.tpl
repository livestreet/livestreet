{**
 * Список всех пользователей
 *}

{extends 'layouts/layout.base.tpl'}

{block name='layout_page_title'}{$aLang.user_list}{/block}

{block name='layout_content'}
	{include 'forms/form.search.users.tpl'}
	{include 'alphanumeric.tpl' aAlphaLetters=$aPrefixUser}

	{* Сортировка *}
	{include 'sort.ajax.tpl'
			 sSortName     = 'sort-user-list'
			 aSortList     = [ [ name => 'user_login',         text => $aLang.sort.by_name ],
							   [ name => 'user_date_register', text => $aLang.user_date_registration ],
							   [ name => 'user_rating',        text => $aLang.user_rating ] ]}

	<div class="js-search-ajax-container" data-type="users">
		{include file='user_list.tpl' aUsersList=$aUsers}
	</div>
{/block}