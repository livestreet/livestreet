{**
 * Список блогов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sMenuHeadItemSelect = 'blogs'}
{/block}

{block 'layout_page_title'}
	{$aLang.blog.blogs}
{/block}

{block 'layout_content'}
	{include 'forms/search_forms/search_form.blogs.tpl'}

	{* Сортировка *}
	{include 'components/sort/sort.ajax.tpl'
			 sSortName     = 'sort-blog-list'
			 sSortSearchType     = 'blogs'
			 aSortList     = [
			 	[ name => 'blog_rating',     text => $aLang.sort.by_rating, order => 'asc' ],
				[ name => 'blog_title',      text => $aLang.sort.by_title ],
				[ name => 'blog_count_user', text => $aLang.blog.sort.by_users ],
				[ name => 'blog_count_topic', text => $aLang.blog.sort.by_topics ]
			 ]}

	<div class="js-search-ajax-container" data-type="blogs">
		{include 'components/blog/blog-list.tpl' aBlogs=$aBlogs bUseMore=true}
	</div>
{/block}