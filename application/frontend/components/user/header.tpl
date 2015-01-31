{**
 * Шапка профиля
 *}

{$component = 'user-profile'}

{$user = $smarty.local.user}
{$mods = $smarty.local.mods}

{if $user->getProfileName()}
    {$mods = "{$mods} has-name"}
{/if}

{if $user->isOnline()}
    {$mods = "{$mods} is-online"}
{/if}

<div class="{$component} {cmods name=$component mods=$mods} {$smarty.local.classes} clearfix" {cattr list=$smarty.local.attributes}>
    {hook run='profile_top_begin' user=$user}

    {* Пользователь *}
    <div class="{$component}-user clearfix">
        <a href="{$user->getUserWebPath()}">
            <img src="{$user->getProfileAvatarPath(100)}" alt="{$user->getProfileName()}" class="{$component}-user-avatar js-user-profile-avatar" itemprop="photo">
        </a>

        <div class="{$component}-user-body">
	        <h2 class="{$component}-user-login" itemprop="nickname">
	            <a href="{$user->getUserWebPath()}">
	                {$user->getLogin()}
	            </a>
	        </h2>

	        {if $user->getProfileName()}
	            <p class="{$component}-user-name" itemprop="name">
	                {$user->getProfileName()|escape}
	            </p>
	        {/if}
        </div>
    </div>

    {* Рейтинг *}
    <div class="{$component}-rating">
        <div class="{$component}-rating-label">Рейтинг</div>
        <div class="{$component}-rating-value">{$user->getRating()}</div>
    </div>

    {hook run='profile_top_end' user=$user}
</div>