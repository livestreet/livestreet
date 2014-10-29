{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{lang 'blog.blocks.search.title'}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} blogs-search"}
{/block}

{block 'block_content'}
	<h3>{lang 'blog.blocks.search.categories.title'}</h3>

	{if $aBlogCategories}
		{$items = [[
			'name'       => 'all',
			'text'       => {lang 'blog.blocks.search.categories.all'},
			'url'        => {router page='blogs'},
			'attributes' => "data-search-type=\"blogs\" data-name=\"category\" data-value=\"0\"",
			'classes'    => 'js-search-ajax-option',
			'count'      => $iCountBlogsAll
		]]}

		{foreach $aBlogCategories as $aCategory}
			{$oCategory=$aCategory.entity}
			{$items[] = [
				'text'       => ($oCategory->getTitle()),
				'url'        => '#',
				'attributes' => "data-search-type=\"blogs\" data-name=\"category\" data-value=\"{$oCategory->getId()}\" style=\"margin-left: {$aCategory.level * 20}px;\"",
				'classes'    => 'js-search-ajax-option',
				'count'      => $oCategory->getCountTargetOfDescendants()
			]}
		{/foreach}

		{include 'components/nav/nav.tpl'
			name       = 'blogs_categories'
			classes    = 'actionbar-item-link'
			activeItem = 'all'
			mods       = 'stacked pills'
			items      = $items}
    {else}
    	{include 'components/alert/alert.tpl' text=$aLang.blog.categories.empty mods='empty'}
    {/if}

    <br>

	{* Тип блога *}
	<h3>{lang 'blog.blocks.search.type.title'}</h3>

	{include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="blogs"' name='type' value=''  checked=true label='Любой'}
	{include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="blogs"' name='type' value='open' label='Открытый'}
	{include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="blogs"' name='type' value='close' label='Закрытый'}
{/block}