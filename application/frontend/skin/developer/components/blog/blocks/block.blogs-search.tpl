{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {lang 'blog.blocks.search.title'}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blogs-search"}
{/block}

{block 'block_content'}
    <h3>{lang 'blog.blocks.search.categories.title'}</h3>

    {if $aBlogCategories}
        {$items = [[
            'name'       => 'all',
            'text'       => {lang 'blog.blocks.search.categories.all'},
            'url'        => {router page='blogs'},
            'attributes' => "data-value=\"0\"",
            'count'      => $iCountBlogsAll
        ]]}

        {foreach $aBlogCategories as $category}
            {$oCategory = $category.entity}

            {$items[] = [
                'text'       => ($oCategory->getTitle()),
                'url'        => '#',
                'attributes' => "data-value=\"{$oCategory->getId()}\" style=\"margin-left: {$category.level * 20}px;\"",
                'count'      => $oCategory->getCountTargetOfDescendants()
            ]}
        {/foreach}

        {include 'components/nav/nav.tpl'
            name       = 'blogs_categories'
            classes    = 'actionbar-item-link'
            attributes = 'id="js-search-ajax-blog-category"'
            activeItem = 'all'
            mods       = 'stacked pills'
            items      = $items}
    {else}
        {include 'components/alert/alert.tpl' text=$aLang.blog.categories.empty mods='empty'}
    {/if}

    <br>

    {* Тип блога *}
    <h3>{lang 'blog.blocks.search.type.title'}</h3>

    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value=''      label='Любой' checked=true}
    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value='open'  label='Открытый'}
    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-blog-type' name='blog_search_type' value='close' label='Закрытый'}
{/block}