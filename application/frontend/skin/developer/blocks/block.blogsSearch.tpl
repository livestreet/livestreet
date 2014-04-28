{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}Поиск по блогам{/block}

{block name='block_content'}
	<h3>{$aLang.block_category_blog}</h3>

	{if $aBlogCategories}
		{$aItems = [ [
			'name'       => 'all',
			'text'       => $aLang.block_category_blog_all,
			'url'        => {router page='blogs'},
			'attributes' => "data-search-type=\"blogs\" data-name=\"category\" data-value=\"0\"",
			'classes'    => 'js-search-ajax-option',
			'count'      => $iCountBlogsAll
		] ]}

		{foreach $aBlogCategories as $oCategory}
			{$aItems[] = [
				'text'       => ($oCategory->getTitle()|escape),
				'url'        => $oCategory->getUrlWeb(),
				'attributes' => "data-search-type=\"blogs\" data-name=\"category\" data-value=\"{$oCategory->getId()}\" style=\"margin-left: {$oCategory->getLevel() * 20}px;\"",
				'classes'    => 'js-search-ajax-option',
				'count'      => $oCategory->getCountBlogs()
			]}
		{/foreach}

		{include 'components/nav/nav.tpl'
				sName       = 'blogs_categories'
				sClasses    = 'actionbar-item-link'
				sActiveItem = 'all'
				sMods       = 'stacked pills'
				aItems      = $aItems}
    {else}
    	{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.categories.empty sMods='empty'}
    {/if}

    <br>

	{* Тип блога *}
	<p class="mb-10">Тип блога</p>
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue=''  bFieldChecked=true sFieldLabel='Любой'}
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue='open' sFieldLabel='Открытый'}
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue='close' sFieldLabel='Закрытый'}
{/block}