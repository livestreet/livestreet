{* Меню пользователя *}
<div class="ls-userbar-user-nav js-userbar-user-nav">
    <a href="{$oUserCurrent->getUserWebPath()}">
        <img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="{$oUserCurrent->getDisplayName()}"" class="ls-userbar-user-nav-avatar" />
    </a>

    <a href="{$oUserCurrent->getUserWebPath()}" class="ls-userbar-user-nav-username">
        {$oUserCurrent->getDisplayName()}
    </a>

    <div class="ls-userbar-user-nav-toggle js-userbar-user-nav-toggle"></div>

    {insert name='block' block='menu' params=[ 'name' => "user"]}
    
</div>