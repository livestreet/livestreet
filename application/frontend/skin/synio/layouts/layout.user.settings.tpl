{**
 * Базовый шаблон настроек пользователя
 *}

{extends './layout.user.tpl'}

{block 'layout_options' append}
    {$layoutNav = [[
        hook       => 'settings',
        activeItem => $sMenuSubItemSelect,
        items => [
            [ 'url' => "{router page='settings'}profile/", 'text' => {lang name='user.settings.nav.profile'}, 'name' => 'profile' ],
            [ 'url' => "{router page='settings'}account/", 'text' => {lang name='user.settings.nav.account'}, 'name' => 'account' ],
            [ 'url' => "{router page='settings'}tuning/",  'text' => {lang name='user.settings.nav.tuning'},  'name' => 'tuning' ],
            [ 'url' => "{router page='settings'}invite/",  'text' => {lang name='user.settings.nav.invites'}, 'name' => 'invite' ]
        ]
    ]]}
{/block}

{block 'layout_user_page_title'}
    {$aLang.user.settings.title}
{/block}