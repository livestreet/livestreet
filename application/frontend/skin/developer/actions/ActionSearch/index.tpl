{**
 * Страница с формой поиска
 *
 * @param array resultItems
 * @param array paging
 * @param array searchType
 * @param array query
 * @param array typeCounts
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.search.search}
{/block}

{block 'layout_content'}
    {include 'components/search/search-form.main.tpl' searchType=$searchType}
    {include 'navs/nav.search.tpl'}

    {if $resultItems}
        {if $searchType == 'topics'}
            {include 'components/topic/topic-list.tpl' topics=$resultItems paging=$paging}
        {elseif $searchType == 'comments'}
            {include 'components/comment/comment-list.tpl' comments=$resultItems}
        {else}
            {hook run='search_result' type=$searchType}
        {/if}
    {elseif $_aRequest.q}
        {include 'components/alert/alert.tpl' text=$aLang.search.alerts.empty mods='empty'}
    {/if}
{/block}