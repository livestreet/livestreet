{**
 * Форма основного поиска (по топикам и комментариям)
 *
 * @styles css/forms.css
 *}

{extends file='forms/form.search.base.tpl'}

{* Форма *}
{block name='search_action'}{router page='search'}topics/{/block}

{* Хуки *}
{block name='search_before'}{hook run='search_begin'}{/block}
{block name='search_begin'}{hook run='search_form_begin'}{/block}
{block name='search_end'}{hook run='search_form_end'}{/block}
{block name='search_after'}{hook run='search_end'}{/block}