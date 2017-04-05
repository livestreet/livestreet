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
            {component 'nav' hook='main' activeItem=$sMenuHeadItemSelect classes='ls-userbar-nav' items=[
                [ 'text' => $aLang.topic.topics,   'url' => {router page='/'},      'name' => 'blog' ],
                [ 'text' => $aLang.blog.blogs,     'url' => {router page='blogs'},  'name' => 'blogs' ],
                [ 'text' => $aLang.user.users,     'url' => {router page='people'}, 'name' => 'people' ],
                [ 'text' => $aLang.activity.title, 'url' => {router page='stream'}, 'name' => 'stream' ]
            ]}
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
                <span class="ls-userbar-notifications-item ls-userbar-rating" title="{lang 'vote.rating'}">
                    {component 'syn-icon' icon='rating'} {$oUserCurrent->getRating()}
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