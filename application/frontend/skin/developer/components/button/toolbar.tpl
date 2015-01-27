{**
 * Тулбар
 *
 * @param array $groups
 *}

{* Название компонента *}
{$component = 'button-toolbar'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'groups', 'classes', 'mods', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{block 'button_toolbar_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes} clearfix" {cattr list=$attributes} role="toolbar">
    {foreach $groups as $group}
        {if is_array( $group )}
            {component 'button' template='group' role='group' params=$group}
        {else}
            {$group}
        {/if}
    {/foreach}
</div>