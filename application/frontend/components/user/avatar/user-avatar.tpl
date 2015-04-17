{**
 * Блок с аватаркой и именем пользователя
 *
 * @param object $user
 * @param string $classes
 * @param array  $attributes
 * @param array  $mods
 *}

{$sizes = [
    'large' => 200,
    'default' => 100,
    'small' => 64,
    'xsmall' => 48,
    'xxsmall' => 24,
    'text' => 18
]}

{$user = $smarty.local.user}

{component 'avatar'
    image   = $user->getProfileAvatarPath( $sizes[ $smarty.local.size|default:'default' ] )
    url     = $user->getUserWebPath()
    classes = 'user-item'
    name    = $user->getDisplayName()
    params  = $smarty.local.params}