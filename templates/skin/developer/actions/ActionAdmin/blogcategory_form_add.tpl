{extends file='modals/modal_base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
{/block}

{block name='id'}modal-category-add{/block}
{block name='class'}js-modal-default{/block}
{block name='title'}
	{if $oCategory}
		{$aLang.admin_blogcategory_form_edit}
	{else}
		{$aLang.admin_blogcategory_form_add}
	{/if}
{/block}

{block name='content'}
	<form action="" method="post" id="form-category-blog-add" onsubmit="ls.admin.{if $oCategory}editCategoryBlog{else}addCategoryBlog{/if}('form-category-blog-add'); return false;">
		{$aLang.admin_blogcategory_form_field_parent}<br/>
		<select name="pid">
			<option value="0"></option>
			{foreach from=$aCategories item=oCategoryItem}
                <option {if $oCategory and $oCategory->getPid()==$oCategoryItem->getId()}selected="selected"{/if} style="margin-left: {$oCategoryItem->getLevel()*20}px;" value="{$oCategoryItem->getId()}">{$oCategoryItem->getTitle()|escape:'html'}</option>
			{/foreach}
		</select>
		<br/>

		{$aLang.admin_blogcategory_form_field_title}<br/>
		<input type="text" name="title" value="{if $oCategory}{$oCategory->getTitle()}{/if}">
		<br/>

		{$aLang.admin_blogcategory_form_field_url}<br/>
        <input type="text" name="url" value="{if $oCategory}{$oCategory->getUrl()}{/if}">
        <br/>

		{$aLang.admin_blogcategory_form_field_sort}<br/>
        <input type="text" name="sort" value="{if $oCategory}{$oCategory->getSort()}{/if}">
        <br/>

		<button type="submit" name="submit" class="button button-primary">{if $oCategory}{$aLang.admin_blogcategory_form_edit_submit}{else}{$aLang.admin_blogcategory_form_add_submit}{/if}</button>

		{if $oCategory}
			<input type="hidden" name="id" value="{$oCategory->getId()}">
		{/if}
	</form>
{/block}