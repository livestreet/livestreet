{extends file='modals/modal_base.tpl'}


{block name='modal_id'}modal-category-add{/block}
{block name='modal_class'}js-modal-default{/block}
{block name='modal_title'}
	{if $oCategory}
		{$aLang.admin_blogcategory_form_edit}
	{else}
		{$aLang.admin_blogcategory_form_add}
	{/if}
{/block}

{block name='modal_content'}
	<form action="" method="post" id="form-category-blog-add" onsubmit="ls.admin.{if $oCategory}editCategoryBlog{else}addCategoryBlog{/if}('form-category-blog-add'); return false;">
		<p><label for="pid">{$aLang.admin_blogcategory_form_field_parent}</label>
		<select name="pid" id="pid" class="width-full">
			<option value="0"></option>
			{foreach $aCategories as $oCategoryItem}
                <option {if $oCategory and $oCategory->getPid()==$oCategoryItem->getId()}selected="selected"{/if} style="margin-left: {$oCategoryItem->getLevel()*20}px;" value="{$oCategoryItem->getId()}">{$oCategoryItem->getTitle()|escape:'html'}</option>
			{/foreach}
		</select></p>
		
		<p><label for="title">{$aLang.admin_blogcategory_form_field_title}</label>
		<input type="text" name="title" id="title" class="width-full" value="{if $oCategory}{$oCategory->getTitle()}{/if}"></p>
		
		<p><label for="url">{$aLang.admin_blogcategory_form_field_url}</label>
        <input type="text" name="url" id="url" class="width-full" value="{if $oCategory}{$oCategory->getUrl()}{/if}"></p>
		
		<label for="sort">{$aLang.admin_blogcategory_form_field_sort}</label>
        <input type="text" name="sort" id="sort" class="width-full" value="{if $oCategory}{$oCategory->getSort()}{/if}">


		{if $oCategory}
			<input type="hidden" name="id" value="{$oCategory->getId()}">
		{/if}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" name="submit" class="button button-primary" onclick="jQuery('#form-category-blog-add').submit()">
		{if $oCategory}{$aLang.admin_blogcategory_form_edit_submit}{else}{$aLang.admin_blogcategory_form_add_submit}{/if}
	</button>
{/block}