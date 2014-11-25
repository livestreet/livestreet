{**
 * Блок с аватаркой и именем пользователя
 *
 * @param object  $user
 * @param integer $avatarSize
 *
 * @param string $classes
 * @param array  $attributes
 * @param array  $mods
 *}

{$component = 'user-item'}

{$user = $smarty.local.user}

<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}"
    {cattr list=$smarty.local.attributes}>

    <a href="{$user->getUserWebPath()}" class="{$component}-avatar-link">
        <img src="{$user->getProfileAvatarPath( $smarty.local.avatarSize|default:24 )}" alt="{$user->getLogin()}" class="{$component}-avatar" />
    </a>

    <a href="{$user->getUserWebPath()}" class="{$component}-name">
        {$user->getDisplayName()}
    </a>
</div>