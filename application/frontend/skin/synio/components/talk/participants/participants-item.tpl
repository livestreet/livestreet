{**
 * 
 *}

{extends 'component@user-list-add.item'}

{block 'user_list_add_item_options' append}
    {component_define_params params=[ 'editable' ]}

    {if $userContainer && $userContainer->getUserActive() != $TALK_USER_ACTIVE}
        {$classes = "$classes inactive"}
        {$attributes = [ 'title' => {lang 'talk.users.inactive'} ]}
    {/if}
{/block}

{block 'user_list_add_item_actions'}
    {if $editable|default:true && $user->getId() != $oUserCurrent->getId()}
        <li class="js-message-users-user-inactivate ls-talk-participants-item-inactivate" title="{$aLang.common.remove}" data-user-id="{$userId}">
            {component 'icon' icon='minus'}
        </li>

        <li class="js-message-users-user-activate ls-talk-participants-item-activate" title="{$aLang.common.add}" data-user-id="{$userId}" data-user-login="{$user->getLogin()}">
            {component 'icon' icon='plus'}
        </li>
    {/if}
{/block}