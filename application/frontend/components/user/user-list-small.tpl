{**
 * Список пользователей с элементами управления
 *
 * @param object  $users
 * @param string  $title
 * @param boolean $hideableEmptyAlert
 * @param boolean $show
 * @param boolean $selectable
 * @param array   $exclude
 * @param string  $itemTemplate
 *}

{$component = 'user-list-small'}
{component_define_params params=[ 'exclude', 'hideableEmptyAlert', 'selectable', 'show', 'title', 'users', 'mods', 'classes', 'attributes' ]}

{* Заголовок *}
{if $title}
    <h3 class="user-list-small-title">{$title}</h3>
{/if}

{* Уведомление о пустом списке *}
{if ! $users || $hideableEmptyAlert}
    {component 'blankslate'
        text    = $aLang.common.empty
        classes = 'js-user-list-small-empty'
        visible = ! $users}
{/if}

{if $selectable}
    {$mods = "$mods selectable"}
{/if}

{* Список пользователей *}
{if $users || ! $show|default:true}
    <ul class="{$component} js-user-list-small {$classes} {cmods name=$component mods=$mods}" {cattr list=$attributes} {if ! $show|default:true}style="display: none"{/if}>
        {foreach $users as $user}
            {$userContainer = $user}

            {if $user->getUser()}
                {$user = $user->getUser()}
            {/if}

            {if ! $exclude || ! in_array( $user->getId(), $exclude )}
                {block 'user_list_small_item'}
                    {component 'user' template='list-small-item' user=$user selectable=$selectable}
                {/block}
            {/if}
        {/foreach}
    </ul>
{/if}