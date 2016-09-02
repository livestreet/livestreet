{**
 * Блок сортировки
 *
 * @param array   $items
 * @param array   $text
 * @param string  $label
 * @param boolean $showLabel
 *}

{$component = 'ls-sort'}
{component_define_params params=[ 'items', 'text', 'label', 'mods', 'classes', 'attributes' ]}

{foreach $items as $item}
    {$items[ $item@key ][ 'attributes' ] = array_merge( $items[ $item@key ][ 'attributes' ]|default:[], [
        'data-name' => 'sort_by',
        'data-value' => $item[ 'name' ],
        'data-order' => $item[ 'order' ]|default:'desc'
    ])}
{/foreach}

<div class="{$component} {$classes}">
    {component 'dropdown'
        text       = $text|default:'...'
        classes    = 'js-dropdown-default'
        attributes = [ 'data-lsdropdown-selectable' => 'true' ]
        menu       = $items}
</div>