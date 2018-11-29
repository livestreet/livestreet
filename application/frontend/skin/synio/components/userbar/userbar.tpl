{**
 * Юзербар
 *}

<div class="ls-userbar js-userbar">
    <div class="ls-userbar-inner ls-clearfix" style="min-width: {Config::Get('view.grid.fluid_min_width')}; max-width: {Config::Get('view.grid.fluid_max_width')};">
        <h1 class="ls-userbar-logo">
            <a href="{router page='/'}">{Config::Get('view.name')}</a>
        </h1>

        {* Главное меню *}
        {block 'nav_main'}
            {insert name='block' block='menu' params=[ 
                "name" => "main",
                "activeItem" => $sMenuHeadItemSelect, 
                "mods" => "main", 
                "classes" => "ls-userbar-nav" ]}
        {/block}

        {if $oUserCurrent}
            <div class="ls-userbar-notifications">
                {hook run='userbar_notifications_items_before'}

                {* Новые сообщения *}
                {if $iUserCurrentCountTalkNew}
                    <a href="{router page='talk'}" class="ls-userbar-notifications-item ls-userbar-messages" title="{lang 'talk.new_messages'}">
                        {component 'syn-icon' icon='message-new'} {$iUserCurrentCountTalkNew}
                    </a>
                {/if}

                {* Рейтинг *}
                <span class="ls-userbar-notifications-item ls-userbar-rating" title="">
                    {component 'syn-icon' icon='rating'} 
                </span>
            </div>

            {* Меню пользователя *}
            {component 'userbar.usernav'}
        {else}
            {* Меню авторизации *}
            {$items = [
                [ 'text' => $aLang.auth.login.title,        'classes' => 'js-modal-toggle-login',        'url' => {router page='auth/login'} ],
                [ 'text' => $aLang.auth.registration.title, 'classes' => 'js-modal-toggle-registration', 'url' => {router page='auth/register'} ]
            ]}

            {component 'nav' hook='auth' classes='ls-userbar-auth-nav' hookParams=[ user => $oUserCurrent ] items=$items}
        {/if}
    </div>
</div>