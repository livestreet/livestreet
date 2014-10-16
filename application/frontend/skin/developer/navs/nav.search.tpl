{**
 * Навигация по результатам поиска
 *}

{include 'components/nav/nav.tpl'
    name       = 'search'
    activeItem = $sSearchType
    mods       = 'pills'
    items      = [
        [ 'name' => 'topics', 'url' => "{router page='search/topics'}?q={$_aRequest.q}", 'text' => $aLang.search.result.topics, 'count' => $aTypeCounts.topics ],
        [ 'name' => 'comments', 'url' => "{router page='search/comments'}?q={$_aRequest.q}", 'text' => $aLang.search.result.comments, 'count' => $aTypeCounts.comments ]
    ]}