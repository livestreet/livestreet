{**
 * Фильтр блогов
 *}

{capture 'block_content'}
    <h3>{lang 'blog.blocks.search.categories.title'}</h3>

    {* Категории *}
    {if $aBlogCategories}
        {$items = [[
            'name'       => 'all',
            'text'       => {lang 'blog.blocks.search.categories.all'},
            'url'        => {router page='blogs'},
            'attributes' => [ 'data-value' => '0' ],
            'count'      => $iCountBlogsAll
        ]]}

        {foreach $aBlogCategories as $category}
            {$oCategory = $category.entity}

            {$items[] = [
                'text'       => ($oCategory->getTitle()),
                'url'        => '#',
                'attributes' => [ 'data-value' => $oCategory->getId(), 'style' => "margin-left: {$category.level * 20}px;" ],
                'count'      => $oCategory->getCountTargetOfDescendants()
            ]}
        {/foreach}

        {component 'nav'
            name       = 'blogs_categories'
            classes    = 'actionbar-item-link'
            attributes = [ 'id' => 'js-search-ajax-blog-category' ]
            activeItem = 'all'
            mods       = 'stacked pills'
            items      = $items}
    {else}
        {component 'alert' text=$aLang.blog.categories.empty mods='empty'}
    {/if}

    <br>

    {* Тип блога *}
    <h3>{lang 'blog.blocks.search.type.title'}</h3>

    <div class="field-checkbox-group">
        {component 'field' template='radio' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value=''      label='Любой' checked=true}
        {component 'field' template='radio' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value='open'  label='Открытый'}
        {component 'field' template='radio' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value='close' label='Закрытый'}
    </div>

    {* Тип принадлежности блога *}
    {if $oUserCurrent}
        <h3>{lang 'blog.blocks.search.relation.title'}</h3>

        <div class="field-checkbox-group">
            {component 'field' template='radio' inputClasses='js-search-ajax-blog-relation' name='blog_search_relation' value='all'  label='Все' checked=true}
            {component 'field' template='radio' inputClasses='js-search-ajax-blog-relation' name='blog_search_relation' value='my'   label='Мои'}
            {component 'field' template='radio' inputClasses='js-search-ajax-blog-relation' name='blog_search_relation' value='join' label='Читаю'}
        </div>
    {/if}

{/capture}

{component 'block'
    mods    = 'blogs-search'
    title   = {lang 'blog.blocks.search.title'}
    content = $smarty.capture.block_content}