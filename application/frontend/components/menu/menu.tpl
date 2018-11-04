{**
 * Главное меню
 *
 * @param string  $mods
 * @param string  $classes
 * @param array   $attributes
 *}

{component_define_params params=[ 'activeItem', 'mods', 'classes' ]}

{component 'nav' hook='main' activeItem=$activeItem mods=$mods classes=$classes params=$params}
