{**
 * Навигация по блогам с возможностью выбрать категорию и блог из этой категории
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	{lang 'blog.blocks.navigator.title'}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} blog-navigation"}
{/block}

{block 'block_content'}
	{if $aNavigatorBlogCategories}
		<p><select class="width-full js-blog-nav-categories">
			<option value="0">{lang 'blog.blocks.navigator.category'}</option>

			{foreach $aNavigatorBlogCategories as $aCategoryItem}
				{$oCategoryItem=$aCategoryItem.entity}
				<option style="margin-left: {$aCategoryItem.level*20}px;" value="{$oCategoryItem->getId()}">{$oCategoryItem->getTitle()}</option>
			{/foreach}
		</select></p>

		<p><select class="width-full js-blog-nav-blogs" disabled>
			<option value="0">{lang 'blog.blocks.navigator.blog'}</option>

			{foreach $aNavigatorBlogs as $oBlogItem}
				<option value="{$oBlogItem->getId()}" data-url="{$oBlogItem->getUrlFull()}">{$oBlogItem->getTitle()|escape}</option>
			{/foreach}
		</select></p>

		{include 'components/button/button.tpl' text={lang 'blog.blocks.navigator.submit'} classes='js-blog-nav-submit' isDisabled=true}
	{else}
		{include 'components/alert/alert.tpl' text={lang 'blog.blocks.navigator.empty'} mods='empty'}
	{/if}
{/block}