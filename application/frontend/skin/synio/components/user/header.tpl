{**
 * Шапка профиля
 *}

{$component = 'user-profile'}
{component_define_params params=[ 'user', 'mods', 'classes', 'attributes' ]}

{if $user->getProfileName()}
    {$mods = "{$mods} has-name"}
{/if}

{if $user->isOnline()}
    {$mods = "{$mods} is-online"}
{/if}

<div class="{$component} {cmods name=$component mods=$mods} {$classes} ls-clearfix" {cattr list=$attributes}>
    {* @hook Начало шапки с информацией о пользователе *}
    {hook run='user_header_begin' user=$user}

    {* Пользователь *}
    <div class="{$component}-user ls-clearfix">
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

    {* @hook Рейтинг пользователя *}
    {hookb run='user_rating' user=$user}
        {$_rating = $user->getRating()}

        <div class="{$component}-rating {if $_rating < 0}{$component}-rating--negative{/if}">
            <div class="{$component}-rating-value">{$_rating}</div>
            <div class="{$component}-rating-label">{lang 'vote.rating'}</div>
        </div>
    {/hookb}

    {* @hook Конец шапки с информацией о пользователе *}
    {hook run='user_header_end' user=$user}
</div>