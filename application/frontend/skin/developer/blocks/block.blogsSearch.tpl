{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	Поиск по блогам
{/block}

{block 'block_options' append}
	{$mods = "{$mods} blogs-search"}
{/block}

{block 'block_content'}
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

		{foreach $aBlogCategories as $aCategory}
			{$oCategory=$aCategory.entity}
			{$aItems[] = [
				'text'       => ($oCategory->getTitle()),
				'url'        => '#',
				'attributes' => "data-search-type=\"blogs\" data-name=\"category\" data-value=\"{$oCategory->getId()}\" style=\"margin-left: {$aCategory.level * 20}px;\"",
				'classes'    => 'js-search-ajax-option',
				'count'      => $oCategory->getCountTargetOfDescendants()
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
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="blogs"' sName='type' sValue=''  bChecked=true sLabel='Любой'}
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="blogs"' sName='type' sValue='open' sLabel='Открытый'}
	{include 'components/field/field.radio.tpl' sInputClasses='js-search-ajax-option' sInputAttributes='data-search-type="blogs"' sName='type' sValue='close' sLabel='Закрытый'}
{/block}