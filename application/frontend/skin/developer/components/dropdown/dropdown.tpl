{**
 * Выпадающее меню
 *
 * @param string text
 * @param string activeItem
 * @param array  menu
 *}

{* Название компонента *}
{$component = 'dropdown'}

{foreach [ 'text', 'activeItem', 'activeItem', 'menu', 'classes', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{if ! $smarty.local.text}
    {$mods = "$mods no-text"}
{/if}

{block 'dropdown_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {* Кнопка *}
    {if $smarty.local.isSplit}
        {component 'button' template='group' buttons=[
            [ 'text' => $smarty.local.text, 'mods' => $mods, 'attributes' => [ 'tabindex' => -1 ] ],
            {component 'button'
                type       = 'button'
                classes    = "{$component}-toggle js-dropdown-toggle"
                mods       = "{$mods} no-text"
                attributes = array_merge( $attributes|default:[], [
                    'aria-haspopup' => 'true',
                    'aria-expanded' => 'false'
                ])}
        ]}
    {else}
        {component 'button'
            type       = 'button'
            classes    = "{$component}-toggle js-dropdown-toggle"
            mods       = $mods
            text       = $smarty.local.text
            attributes = array_merge( $attributes|default:[], [
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false'
            ])}
    {/if}

    {* Меню *}
    {include './dropdown-menu.tpl' activeItem=$activeItem items=$menu}
</div>