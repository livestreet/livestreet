{**
 * Активность
 *}

{extends './layout.base.tpl'}

{block 'layout_options' append}
    {$layoutNav = [[
        hook       => 'activity',
        activeItem => $sMenuItemSelect,
        items => [
            [ 'name' => 'user', 'url' => "{router page='stream'}personal/", 'text' => $aLang.activity.nav.personal, 'is_enabled' => !! $oUserCurrent ],
            [ 'name' => 'all',  'url' => "{router page='stream'}all/", 'text' => $aLang.activity.nav.all ]
        ]
    ]]}
{/block}

{block 'layout_page_title'}
    {$aLang.activity.title}
{/block}