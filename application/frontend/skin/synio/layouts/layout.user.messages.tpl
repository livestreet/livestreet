{**
 * Базовый шаблон личных сообщений
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
    {$layoutNav = [[
        hook       => 'talk',
        activeItem => $sMenuSubItemSelect,
        items => [
            [ 'name' => 'inbox',      'url' => "{router page='talk'}",            'text' => $aLang.talk.nav.inbox ],
            [ 'name' => 'new',        'url' => "{router page='talk'}inbox/new/",  'text' => $aLang.talk.nav.new, 'count' => $iUserCurrentCountTalkNew, 'is_enabled' => $iUserCurrentCountTalkNew ],
            [ 'name' => 'add',        'url' => "{router page='talk'}add/",        'text' => $aLang.talk.nav.add ],
            [ 'name' => 'favourites', 'url' => "{router page='talk'}favourites/", 'text' => $aLang.talk.nav.favourites, 'count' => $iCountTalkFavourite ],
            [ 'name' => 'blacklist',  'url' => "{router page='talk'}blacklist/",  'text' => $aLang.talk.nav.blacklist ]
        ]
    ]]}
{/block}

{block 'layout_user_page_title'}
    {$aLang.talk.title}
{/block}