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
{block name='search_input_classes'}js-search-ajax-option js-search-text-main{/block}
{block name='search_input_attributes'}data-type="users"{/block}