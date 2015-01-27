{**
 * Группировка кнопок
 *
 * @param string $role group|toolbar
 * @param array  $buttonParams
 * @param array  $buttons
 *}

{* Название компонента *}
{$component = 'button-group'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'role', 'buttons', 'buttonParams', 'classes', 'mods', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{block 'button_group_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes} clearfix" {cattr list=$attributes} role="{$role|default:'group'}">
    {foreach $buttons as $button}
        {if is_array( $button )}
            {block 'button_group_button'}
                {component 'button' params=array_merge( $buttonParams|default:[], $button )}
            {/block}
        {else}
            {$button}
        {/if}
    {/foreach}
</div>