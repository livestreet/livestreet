{**
 * Юзербар
 *}

<div class="userbar">
    <div class="userbar-inner clearfix" style="min-width: {Config::Get('view.grid.fluid_min_width')}; max-width: {Config::Get('view.grid.fluid_max_width')};">
        {if ! Config::Get( 'view.layout_show_banner' )}
            <h1 class="userbar-logo">
                <a href="{router page='/'}">{Config::Get('view.name')}</a>
            </h1>
        {/if}

        <nav class="userbar-nav">
            {if $oUserCurrent}
                {$items = [
                    [
                        'text'       => "<img src=\"{$oUserCurrent->getProfileAvatarPath(24)}\" alt=\"{$oUserCurrent->getDisplayName()}\" class=\"avatar\" /> {$oUserCurrent->getDisplayName()}",
                        'url'        => "{$oUserCurrent->getUserWebPath()}",
                        'classes'    => 'nav-item--userbar-username',
                        'menu'       => [
                            [ 'name' => 'whois',      'text' => {lang name='user.profile.nav.info'},         'url' => "{$oUserCurrent->getUserWebPath()}" ],
                            [ 'name' => 'wall',       'text' => {lang name='user.profile.nav.wall'},         'url' => "{$oUserCurrent->getUserWebPath()}wall/", 'count' => $iCountWallUser ],
                            [ 'name' => 'created',    'text' => {lang name='user.profile.nav.publications'}, 'url' => "{$oUserCurrent->getUserWebPath()}created/topics/", 'count' => $iCountCreated ],
                            [ 'name' => 'favourites', 'text' => {lang name='user.profile.nav.favourite'},    'url' => "{$oUserCurrent->getUserWebPath()}favourites/topics/", 'count' => $iCountFavourite ],
                            [ 'name' => 'friends',    'text' => {lang name='user.profile.nav.friends'},      'url' => "{$oUserCurrent->getUserWebPath()}friends/", 'count' => $iCountFriendsUser ],
                            [ 'name' => 'activity',   'text' => {lang name='user.profile.nav.activity'},     'url' => "{$oUserCurrent->getUserWebPath()}stream/" ],
                            [ 'name' => 'talk',       'text' => {lang name='user.profile.nav.messages'},     'url' => "{router page='talk'}", 'count' => $iUserCurrentCountTalkNew ],
                            [ 'name' => 'settings',   'text' => {lang name='user.profile.nav.settings'},     'url' => "{router page='settings'}" ],
                            [ 'name' => 'admin',      'text' => {lang name='admin.title'},                   'url' => "{router page='admin'}", 'is_enabled' => $oUserCurrent && $oUserCurrent->isAdministrator() ]
                        ]
                    ],
                    [ 'text' => $aLang.common.create, 'url' => "{router page='content'}add/topic", 'attributes' => 'data-modal-target="modal-write"' ],
                    [ 'text' => $aLang.talk.title,   'url' => "{router page='talk'}", 'title' => $aLang.talk.new_messages, 'is_enabled' => $iUserCurrentCountTalkNew, 'count' => $iUserCurrentCountTalkNew ],
                    [ 'text' => $aLang.auth.logout,  'url' => "{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]
                ]}
            {else}
                {$items = [
                    [ 'text' => $aLang.auth.login.title,        'classes' => 'js-modal-toggle-login',        'url' => {router page='login'} ],
                    [ 'text' => $aLang.auth.registration.title, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='registration'} ]
                ]}
            {/if}

            {component 'nav' name='userbar' activeItem=$sMenuHeadItemSelect mods='userbar' items=$items}
        </nav>

        {component 'search' template='main' mods='light'}
    </div>
</div>

{if $oUserCurrent}
    {component 'modal-create'}
{/if}