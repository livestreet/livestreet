{**
 * Меню
 *
 * @param string  $mods
 * @param string  $classes
 * @param array   $attributes
 *}

{component_define_params params=[ 'activeItem', 'mods', 'classes', 'template' ]} 

{component "menu.{$template}" params=$params activeItem=$activeItem mods=$mods classes=$classes}
