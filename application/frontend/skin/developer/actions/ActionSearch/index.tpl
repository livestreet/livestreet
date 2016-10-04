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

{block 'layout_content_header' prepend}
    {component 'search.main' searchType=$searchType}
{/block}

{block 'layout_options' append}
    {$layoutNav = [[
        name       => 'search',
        activeItem => $searchType,
        items => [
            [ 'name' => 'topics', 'url' => "{router page='search/topics'}?q={$_aRequest.q}", 'text' => $aLang.search.result.topics, 'count' => $typeCounts.topics ],
            [ 'name' => 'comments', 'url' => "{router page='search/comments'}?q={$_aRequest.q}", 'text' => $aLang.search.result.comments, 'count' => $typeCounts.comments ]
        ]
    ]]}
{/block}

{block 'layout_content'}
    {if $resultItems}
        {if $searchType == 'topics'}
            {component 'topic' template='list' topics=$resultItems paging=$paging}
        {elseif $searchType == 'comments'}
            {component 'comment' template='list' comments=$resultItems paging=$paging}
        {else}
            {hook run='search_result' type=$searchType}
        {/if}
    {elseif $_aRequest.q}
        {component 'blankslate' text=$aLang.search.alerts.empty}
    {/if}
{/block}