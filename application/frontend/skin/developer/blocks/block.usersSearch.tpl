{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}Поиск по пользователям{/block}

{block name='block_content'}
    {$aSex = [
        [ 'value' => 'man',   'text' => $aLang.settings_profile_sex_man ],
        [ 'value' => 'woman', 'text' => $aLang.settings_profile_sex_woman ],
        [ 'value' => 'other', 'text' => $aLang.settings_profile_sex_other ]
    ]}

    {include file='forms/fields/form.field.select.tpl'
             sFieldName            = 'profile_sex'
             sFieldLabel           = $aLang.settings_profile_sex
             aFieldItems           = $aSex
             sFieldClasses         = 'width-full js-search-ajax-option'
             sFieldInputAttributes = 'data-search-type="users"'}

	{* Сейчас на сайте *}
	{include file='forms/fields/form.field.checkbox.tpl'
			 sFieldName            = 'is_online'
			 sFieldInputClasses    = 'js-search-ajax-option'
			 sFieldInputAttributes = 'data-search-type="users"'
			 bFieldChecked         = false
			 sFieldLabel           = 'Сейчас на сайте'}

	{* Пол *}
	<p class="mb-10">Пол</p>
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='null' bFieldChecked=true sFieldLabel='Любой'}
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='male' sFieldLabel='Мужской'}
	{include 'forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="users"' sFieldName='sex' sFieldValue='female' sFieldLabel='Женский'}
{/block}