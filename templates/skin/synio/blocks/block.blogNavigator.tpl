{if $aNavigatorBlogCategories}
	<section class="block block-type-blog-navigation">
		<header class="block-header">
			<h3>{$aLang.block_blog_navigator}</h3>
		</header>


		<div class="block-content">
			<select id="blog-navigator-category" class="width-full blog-navigator-categories" onchange="ls.blog.loadBlogsByCategory($(this).val());">
				<option value="0">{$aLang.blog_category}</option>
				{foreach from=$aNavigatorBlogCategories item=oCategoryItem}
                    <option style="margin-left: {$oCategoryItem->getLevel()*20}px;" value="{$oCategoryItem->getId()}">{$oCategoryItem->getTitle()|escape:'html'}</option>
				{/foreach}
            </select>
			<br/><br/>
            <select id="blog-navigator-blog" class="width-full" disabled>
            	<option value="0">{$aLang.blog}</option>
				{foreach from=$aNavigatorBlogs item=oBlogItem}
                    <option value="{$oBlogItem->getId()}" data-url="{$oBlogItem->getUrlFull()}">{$oBlogItem->getTitle()|escape:'html'}</option>
				{/foreach}
            </select>
            <br/><br/>
			<button onclick="ls.blog.navigatorGoSelectBlog();" class="button" id="blog-navigator-button" disabled>{$aLang.block_blog_navigator_button}</button>
		</div>
	</section>
{/if}