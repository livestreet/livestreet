{**
 * Навигация по результатам поиска
 *}

{component 'nav'
    name       = 'search'
    activeItem = $searchType
    mods       = 'pills'
    items      = [
        [ 'name' => 'topics', 'url' => "{router page='search/topics'}?q={$_aRequest.q}", 'text' => $aLang.search.result.topics, 'count' => $typeCounts.topics ],
        [ 'name' => 'comments', 'url' => "{router page='search/comments'}?q={$_aRequest.q}", 'text' => $aLang.search.result.comments, 'count' => $typeCounts.comments ]
    ]}