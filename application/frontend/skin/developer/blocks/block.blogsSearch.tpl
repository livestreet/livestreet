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
		<ul class="nested-list">
			<li class="nested-list-item active js-search-ajax-option" data-search-type="blogs" data-name="category" data-value="0">
				<a href="{router page='blogs'}">{$aLang.block_category_blog_all} ({$iCountBlogsAll})</a>
			</li>

			{foreach $aBlogCategories as $oCategory}
				<li class="nested-list-item js-search-ajax-option" data-search-type="blogs" data-name="category" data-value="{$oCategory->getId()}">
					<a style="margin-left: {$oCategory->getLevel() * 20}px;" href="{$oCategory->getUrlWeb()}">{$oCategory->getTitle()|escape} ({$oCategory->getCountBlogs()})</a>
				</li>
			{/foreach}
        </ul>
    {else}
    	{include 'alert.tpl' mAlerts=$aLang.blog.categories.empty sAlertStyle='empty'}
    {/if}

    <br>

	{* Тип блога *}
	<p class="mb-10">Тип блога</p>
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue='null'  bFieldChecked=true sFieldLabel='Любой'}
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue='public' sFieldLabel='Открытый'}
	{include file='forms/fields/form.field.radio.tpl' sFieldInputClasses='js-search-ajax-option' sFieldInputAttributes='data-search-type="blogs"' sFieldName='type' sFieldValue='private' sFieldLabel='Закрытый'}
{/block}