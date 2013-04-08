{if $aBlogCategories}
	<section class="block block-type-blog-categories">
		<header class="block-header">
			<h3>{$aLang.block_category_blog}</h3>

			{if $oUserCurrent and $oUserCurrent->isAdministrator()}
				<a href="{router page="admin"}blogcategory/" title="{$aLang.admin_list_blogcategory}" class="icon-cog blog-categories-admin"></a>
			{/if}
		</header>


		<div class="block-content">
			<ul class="blog-category-list">
				<li {if !$oBlogCategoryCurrent}class="active"{/if}><a href="{router page='blogs'}">{$aLang.block_category_blog_all} ({$iCountBlogsAll})</a></li>
				{foreach from=$aBlogCategories item=oCategory}
					<li {if $oBlogCategoryCurrent and $oBlogCategoryCurrent->getId()==$oCategory->getId()}class="active"{/if}><a style="margin-left: {$oCategory->getLevel()*20}px;" href="{$oCategory->getUrlWeb()}">{$oCategory->getTitle()|escape:'html'} ({$oCategory->getCountBlogs()})</a></li>
				{/foreach}
            </ul>
		</div>
	</section>
{/if}