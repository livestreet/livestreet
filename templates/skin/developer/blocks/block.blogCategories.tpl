{**
 * Категории блогов
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
	{assign var='noNav' value=true}
{/block}

{block name='title'}{$aLang.block_category_blog}{/block}
{block name='type'}blog-categories{/block}

{block name='header_end'}
	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		<a href="{router page="admin"}blogcategory/" title="{$aLang.admin_list_blogcategory}" class="icon-cog blog-categories-admin"></a>
	{/if}
{/block}

{block name='content'}
	{if $aBlogCategories}
		<ul class="blog-category-list">
			<li {if !$oBlogCategoryCurrent}class="active"{/if}><a href="{router page='blogs'}">{$aLang.block_category_blog_all} ({$iCountBlogsAll})</a></li>
			{foreach from=$aBlogCategories item=oCategory}
				<li {if $oBlogCategoryCurrent and $oBlogCategoryCurrent->getId()==$oCategory->getId()}class="active"{/if}><a style="margin-left: {$oCategory->getLevel()*20}px;" href="{$oCategory->getUrlWeb()}">{$oCategory->getTitle()|escape:'html'} ({$oCategory->getCountBlogs()})</a></li>
			{/foreach}
        </ul>
    {else}
    	No categories {* TODO: Language *}
    {/if}
{/block}