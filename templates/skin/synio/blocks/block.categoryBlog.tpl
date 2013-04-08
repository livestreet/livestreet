{if $aBlogCategories}
	<section class="block">
		<header class="block-header">
			<h3>{$aLang.block_category_blog}</h3>
		</header>


		<div class="block-content">
			<ul>
				<li {if !$oBlogCategoryCurrent}class="active"{/if}><a href="{router page='blogs'}">{$aLang.block_category_blog_all} ({$iCountBlogsAll})</a></li>
				{foreach from=$aBlogCategories item=oCategory}
					<li {if $oBlogCategoryCurrent and $oBlogCategoryCurrent->getId()==$oCategory->getId()}class="active"{/if}><a style="margin-left: {$oCategory->getLevel()*20}px;" href="{$oCategory->getUrlWeb()}">{$oCategory->getTitle()|escape:'html'} ({$oCategory->getCountBlogs()})</a></li>
				{/foreach}
            </ul>
		</div>
	</section>
{/if}