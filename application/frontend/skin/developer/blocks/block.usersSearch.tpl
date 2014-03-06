{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}Поиск по пользователям{/block}

{block name='block_content'}
	{* Сейчас на сайте *}
	{include file='forms/fields/form.field.checkbox.tpl'
			 sFieldName            = 'is_online'
			 sFieldInputClasses    = 'js-search-ajax-option'
			 sFieldInputAttributes = 'data-search-type="users"'
			 bFieldChecked         = false
			 sFieldLabel           = 'Сейчас на сайте'}

	{* Пол *}
	<p class="mb-10">Пол</p>
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='' bFieldChecked=true sFieldLabel='Любой'}
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='man' sFieldLabel='Мужской'}
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='woman' sFieldLabel='Женский'}
{/block}