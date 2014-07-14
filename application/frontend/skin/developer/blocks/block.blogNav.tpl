{**
 * Навигация по блогам с возможностью выбрать категорию и блог из этой категории
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.block_blog_navigator}{/block}
{block 'block_type'}blog-navigation{/block}

{block 'block_content'}
	{if $aNavigatorBlogCategories}
		<p><select class="width-full js-blog-nav-categories">
			<option value="0">{$aLang.blog.categories.category}</option>

			{foreach $aNavigatorBlogCategories as $aCategoryItem}
				{$oCategoryItem=$aCategoryItem.entity}
				<option style="margin-left: {$aCategoryItem.level*20}px;" value="{$oCategoryItem->getId()}">{$oCategoryItem->getTitle()}</option>
			{/foreach}
		</select></p>

		<p><select class="width-full js-blog-nav-blogs" disabled>
			<option value="0">{$aLang.blog.blog}</option>

			{foreach $aNavigatorBlogs as $oBlogItem}
				<option value="{$oBlogItem->getId()}" data-url="{$oBlogItem->getUrlFull()}">{$oBlogItem->getTitle()|escape}</option>
			{/foreach}
		</select></p>

		{include 'components/button/button.tpl' sText=$aLang.block_blog_navigator_button sClasses='js-blog-nav-submit' bIsDisabled=true}
	{else}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.categories.empty sMods='empty'}
	{/if}
{/block}