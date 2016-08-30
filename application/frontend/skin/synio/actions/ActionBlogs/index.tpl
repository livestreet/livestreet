{**
 * Список блогов
 *
 * @param array   $blogs
 * @param integer $searchCount
 * @param array   $paging
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$sMenuHeadItemSelect = 'blogs'}
{/block}

{block 'layout_page_title'}
    {$aLang.blog.blogs}
{/block}

{block 'layout_content'}
    {component 'blog' template='search-form'}

    {* Сортировка *}
    {component 'sort' template='ajax'
        classes = 'js-search-sort js-search-sort-menu'
        text = $aLang.blog.sort.by_users
        items = [
            [ name => 'blog_count_user',  text => $aLang.blog.sort.by_users ],
            [ name => 'blog_count_topic', text => $aLang.blog.sort.by_topics ],
            [ name => 'blog_title',       text => $aLang.sort.by_title ]
        ]}

    {* Список блогов *}
    <div class="js-search-ajax-blog">
        <h3 class="h3 js-blog-list-search-title" style="display: none"></h3>

        {component 'blog' template='list' blogs=$blogs useMore=true}
    </div>
{/block}