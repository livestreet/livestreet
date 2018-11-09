{**
 * Юзербар
 *}

<div class="ls-userbar">
    <div class="ls-userbar-inner ls-clearfix" style="min-width: {Config::Get('view.grid.fluid_min_width')}; max-width: {Config::Get('view.grid.fluid_max_width')};">
        {if ! Config::Get( 'view.layout_show_banner' )}
            <h1 class="ls-userbar-logo">
                <a href="{router page='/'}">{Config::Get('view.name')}</a>
            </h1>
        {/if}

        <nav class="ls-userbar-nav">
            {if $oUserCurrent}
                {$createMenu = []}

                {foreach $LS->Topic_GetTopicTypes() as $type}
                    {$createMenu[] = [ 'name' => $type->getCode(), 'text' => $type->getName(), 'url' => $type->getUrlForAdd() ]}
                {/foreach}

                {$createMenu[] = [ 'name' => 'blog', 'text' => {lang 'modal_create.items.blog'}, 'url' => {router page='blog/add'} ]}
                {$createMenu[] = [ 'name' => 'talk', 'text' => {lang 'modal_create.items.talk'}, 'url' => {router page='talk/add'} ]}
                {$createMenu[] = [ 'name' => 'drafts', 'text' => {lang 'topic.drafts'}, 'url' => "{router page='content/drafts'}", count => $iUserCurrentCountTopicDraft ]}

                {$items = [
                    [
                        'html' => {insert name='block' block='menu' params=[ 'name' => "user" ]}                        
                    ],
                    [ 'text' => $aLang.common.create, menu => [ hook => 'create', items => $createMenu ] ],
                    [ 'text' => $aLang.talk.title,   'url' => "{router page='talk'}", 'title' => $aLang.talk.new_messages, 'is_enabled' => $iUserCurrentCountTalkNew, 'count' => $iUserCurrentCountTalkNew ],
                    [ 'text' => $aLang.auth.logout,  'url' => "{router page='auth'}logout/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]
                ]}
            {else}
                {$items = [
                    [ 'text' => $aLang.auth.login.title,        'classes' => 'js-modal-toggle-login',        'url' => {router page='auth/login'} ],
                    [ 'text' => $aLang.auth.registration.title, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='auth/register'} ]
                ]}
            {/if}

            {component 'nav' hook='userbar_nav' hookParams=[ user => $oUserCurrent ] activeItem=$sMenuHeadItemSelect mods='userbar' items=$items}
        </nav>

        {component 'search' template='main' mods='light'}
    </div>
</div>
