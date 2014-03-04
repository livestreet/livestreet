{**
 * Список блогов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sMenuHeadItemSelect = 'blogs'}
{/block}

{block 'layout_page_title'}{$aLang.blog.blogs}{/block}

{block 'layout_content'}
	{include 'forms/form.search.blogs.tpl'}

	{* Сортировка *}
	{include 'sort.ajax.tpl'
			 sSortName     = 'sort-blog-list'
			 aSortList     = [ [ name => 'blog_title',      text => $aLang.sort.by_name ],
							   [ name => 'blog_count_user', text => $aLang.blog.sort.by_users ],
							   [ name => 'blog_rating',     text => $aLang.sort.by_rating ] ]}

	<div class="js-search-ajax-container" data-type="blogs">
		{include 'actions/ActionBlogs/blog_list.tpl'}
	</div>
{/block}