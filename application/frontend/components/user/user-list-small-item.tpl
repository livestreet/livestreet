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
{component_define_params params=[ 'selectable', 'user', 'mods', 'classes', 'attributes' ]}

{block 'user_list_small_item_options'}
    {$userId = $user->getId()}
{/block}

<li class="{$component} js-user-list-small-item {$classes}" data-user-id="{$userId}" {cattr list=$attributes}>
    {* Чекбокс *}
    {if $selectable}
        <input type="checkbox" class="js-user-list-small-checkbox" data-user-id="{$userId}" data-user-login="{$user->getLogin()}" />
    {/if}

    {* Пользователь *}
    {block 'user_list_small_item_content'}
        {component 'user' template='avatar' size='xxsmall' mods='inline' user=$user}
    {/block}
</li>