{**
 * Выпадающее меню
 *
 * @param string text
 * @param string activeItem
 * @param boolean isSplit
 * @param array  menu
 *}

{* Название компонента *}
{$component = 'ls-dropdown'}
{component_define_params params=[ 'text', 'icon', 'activeItem', 'isSplit', 'menu', 'mods', 'classes', 'attributes' ]}

{if ! $text}
    {$mods = "$mods no-text"}
{/if}

{block 'dropdown_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes} ls-nav--dropdown" {cattr list=$attributes}>
    {* Кнопка *}
    {if $isSplit}
        {component 'button' template='group' buttons=[
            [ 'text' => $text, 'mods' => $mods, 'attributes' => [ 'tabindex' => -1 ] ],
            {component 'button'
                type       = 'button'
                classes    = "{$component}-toggle js-{$component}-toggle"
                mods       = "{$mods} no-text"
                attributes = array_merge( $attributes|default:[], [
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ])}
        ]}
    {else}
        {component 'button'
            type       = 'button'
            classes    = "{$component}-toggle js-{$component}-toggle"
            mods       = $mods
            text       = $text
            icon       = $icon
            attributes = array_merge( $attributes|default:[], [
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false'
            ])}
    {/if}

    {* Меню *}
    {component 'dropdown' template='menu' activeItem=$activeItem items=$menu}
</div>
