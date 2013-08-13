{**
 * Форма поиска блогов
 *
 * @styles css/forms.css
 *}

{extends file='forms/form.search.base.tpl'}

{block name='search_before'}
	{$bNoSubmitButton = true}
{/block}

{* Форма *}
{block name='search_type'}blog{/block}
{block name='search_method'}post{/block}
{block name='search_attributes'}id="form-users-search" onsubmit="return false;"{/block}

{* Поле ввода *}
{block name='search_input_placeholder'}{$aLang.user_search_title_hint}{/block}
{block name='search_input_name'}user_login{/block}
{block name='search_input_attributes'}
	id="search-user-login"
	onkeyup="ls.timer.run(ls.user.searchUsers,'users_search',['form-users-search'],1000);"
{/block}

{* Алфавитный указатель *}
{block name='search_input_after'}
	<ul id="user-prefix-filter" class="search-form-alphabet">
		<li class="active"><a href="#" onclick="return ls.user.searchUsersByPrefix('',this);"><span>{$aLang.user_search_filter_all}</span></a></li>

		{foreach $aPrefixUser as $sPrefixUser}
			<li><a href="#" onclick="return ls.user.searchUsersByPrefix('{$sPrefixUser}',this);"><span>{$sPrefixUser}</span></a></li>
		{/foreach}
	</ul>
{/block}