{**
 * 
 *}

{extends 'Component@user-list-add.item'}

{block 'user_list_add_item_options' append}
    {if $userContainer && $userContainer->getUserActive() != $TALK_USER_ACTIVE}
        {$classes = "$classes inactive"}
        {$attributes = [ 'title' => 'Пользователь не участвует в разговоре' ]}
    {/if}
{/block}

{block 'user_list_add_item_actions'}
    {if $smarty.local.editable|default:true}
        <li class="icon-minus js-message-users-user-inactivate" title="{$aLang.common.remove}" data-user-id="{$userId}"></li>
        <li class="icon-plus js-message-users-user-activate" title="{$aLang.common.add}" data-user-id="{$userId}" data-user-login="{$user->getLogin()}"></li>
    {/if}
{/block}