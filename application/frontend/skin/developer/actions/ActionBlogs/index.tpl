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
    {include 'components/blog/search-form.blogs.tpl'}

    {* Сортировка *}
    {include 'components/sort/sort.ajax.tpl'
        classes = 'js-search-sort'
        items = [
            [ name => 'blog_count_user',  text => $aLang.blog.sort.by_users ],
            [ name => 'blog_count_topic', text => $aLang.blog.sort.by_topics ],
            [ name => 'blog_title',       text => $aLang.sort.by_title ]
        ]}

    <div class="js-search-ajax-blog">
        {include 'components/blog/blog-list.tpl' blogs=$aBlogs useMore=true pagination=$aPaging}
    </div>
{/block}