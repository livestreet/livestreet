{**
 * Форма поиска по тегам
 *
 * @styles css/forms.css
 *}

{extends file='forms/form.search.base.tpl'}

{* Форма *}
{block name='search_type'}tags{/block}
{block name='search_classes'}js-tag-search-form{/block}

{* Поле ввода *}
{block name='search_input_value'}{$sTag|escape:'html'}{/block}
{block name='search_input_placeholder'}{$aLang.block_tags_search}{/block}
{block name='search_input_classes'}autocomplete-tags js-tag-search{/block}
{block name='search_input_name'}tag{/block}