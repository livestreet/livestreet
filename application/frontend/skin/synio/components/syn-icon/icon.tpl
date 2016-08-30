{$component = 'syn-icon'}
{component_define_params params=[ 'icon', 'mods', 'classes', 'attributes' ]}

<i class="{$component} {$component}-{$icon} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}></i>