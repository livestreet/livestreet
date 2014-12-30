{**
 * Иконка
 *
 * @param string $icon
 * @param string $classes
 * @param string $mods
 * @param array  $attributes
 *}

{$component = 'icon'}

<i class="{$component} {$component}-{$smarty.local.icon} {cmods name=$component mods=$mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}></i>