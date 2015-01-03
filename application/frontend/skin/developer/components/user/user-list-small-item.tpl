{**
 * Список пользователей с элементами управления / Пользователь
 *
 * @param object  $user
 * @param string  $selectable
 * @param string  $showActions
 * @param string  $showRemove
 *
 * @param string $classes
 * @param array  $attributes
 * @param array  $mods
 *}

{$component = 'user-list-small-item'}

{block 'user_list_small_item_options'}
    {$classes = $smarty.local.classes}
    {$attributes = $smarty.local.attributes}
    {$user = $smarty.local.user}
    {$userId = $user->getId()}
{/block}

<li class="{$component} js-user-list-small-item {$classes}" data-user-id="{$userId}" {cattr list=$attributes}>
    {* Чекбокс *}
    {if $smarty.local.selectable}
        <input type="checkbox" class="js-user-list-small-checkbox" data-user-id="{$userId}" data-user-login="{$user->getLogin()}" />
    {/if}

    {* Пользователь *}
    {block 'user_list_small_item_content'}
        {component 'user' template='item' user=$user}
    {/block}
</li>