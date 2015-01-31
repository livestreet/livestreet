{**
 * Блок сортировки
 *
 * @param array   $items
 * @param string  $label
 * @param boolean $showLabel
 *}

{$component = 'sort'}

{$items = $smarty.local.items}
{$classes = "{$smarty.local.classes} sort"}

{foreach $items as $item}
    {$items[ $item@key ][ 'attributes' ] = array_merge( $items[ $item@key ][ 'attributes' ]|default:[], [
        'data-name' => 'sort_by',
        'data-value' => $item[ 'name' ],
        'data-order' => $item[ 'order' ]|default:'desc'
    ])}
{/foreach}

{component 'button' template='group' classes=$classes params=$smarty.local.params buttons=[
    [ 'text' => $smarty.local.label|default:$aLang.sort.label, 'isDisabled' => true ],
    {component 'dropdown'
        text       = $smarty.local.text|default:'...'
        classes    = 'js-dropdown-default'
        attributes = [ 'data-lsdropdown-selectable' => 'true' ]
        menu       = $items}
]}