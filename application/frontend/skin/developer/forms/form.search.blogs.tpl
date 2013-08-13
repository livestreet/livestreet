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
{block name='search_attributes'}id="form-blogs-search" onsubmit="return false;"{/block}

{* Поле ввода *}
{block name='search_input_placeholder'}{$aLang.blogs_search_title_hint}{/block}
{block name='search_input_name'}blog_title{/block}
{block name='search_input_attributes'}
	onkeyup="ls.timer.run(ls.blog.searchBlogs,'blogs_search',['form-blogs-search'],1000);"
{/block}