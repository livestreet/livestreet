{**
 * Список блогов
 *
 * @param array   $blogs
 * @param array   $pagination
 * @param boolean $useMore
 * @param boolean $hideMore
 * @param string  $textEmpty
 *}

{component_define_params params=[ 'blogs', 'pagination', 'useMore', 'hideMore', 'textEmpty' ]}

{if $blogs}
    {* Список блогов *}
    {component 'item' template='group'
        classes = 'js-more-blogs-container'
        items   = {component 'blog' template='list-loop' blogs=$blogs}}

    {* Кнопка подгрузки *}
    {if $useMore}
        {if ! $hideMore}
            {component 'more'
                classes    = 'js-more-search'
                target     = '.js-more-blogs-container'
                ajaxParams = [ 'next_page' => 2 ]}
        {/if}
    {else}
        {component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/{$pagination.sGetParams}"}
    {/if}
{else}
    {component 'blankslate' text=$textEmpty|default:{lang name='blog.alerts.empty'}}
{/if}