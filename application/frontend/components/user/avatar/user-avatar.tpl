{**
 * Блок с аватаркой и именем пользователя
 *
 * @param object $user
 * @param string $classes
 * @param array  $attributes
 * @param array  $mods
 *}

{component_define_params params=[ 'user', 'size' ]}

{$sizes = [
    'large' => 200,
    'default' => 100,
    'small' => 64,
    'xsmall' => 48,
    'xxsmall' => 24,
    'text' => 24
]}

{component 'avatar'
    image   = $user->getProfileAvatarPath( $sizes[ $size|default:'default' ] )
    url     = $user->getUserWebPath()
    classes = 'user-item'
    name    = $user->getDisplayName()
    params  = $params}