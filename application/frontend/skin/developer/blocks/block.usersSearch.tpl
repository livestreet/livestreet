{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}Поиск по пользователям{/block}

{block name='block_content'}
	{* Сейчас на сайте *}
	{include file='components/field/field.checkbox.tpl'
			 sName            = 'is_online'
			 sInputClasses    = 'js-search-ajax-option'
			 sInputAttributes = 'data-search-type="users"'
			 bChecked         = false
			 sLabel           = 'Сейчас на сайте'}

	{* Пол *}
	<p class="mb-10">Пол</p>
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="users"' sName='sex' sValue='' bChecked=true sLabel='Любой'}
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="users"' sName='sex' sValue='man' sLabel='Мужской'}
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="users"' sName='sex' sValue='woman' sLabel='Женский'}
{/block}