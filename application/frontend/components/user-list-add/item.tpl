{extends 'component@user.user-list-small-item'}

{block 'user_list_small_item_options' append}
    {component_define_params params=[ 'showActions', 'showRemove' ]}

    {block 'user_list_add_item_options'}{/block}
{/block}

{block 'user_list_small_item_content' append}
    {block 'user_list_add_item_content'}
        {* Действия *}
        {if $showActions}
            <ul class="{$component}-actions js-user-list-small-actions">
                {block 'user_list_add_item_actions'}
                    {if $showRemove|default:true}
                        <li class="js-user-list-add-user-remove" title="{$aLang.common.remove}" data-user-id="{$userId}">
                            {component 'icon' icon='remove'}
                        </li>
                    {/if}
                {/block}
            </ul>
        {/if}
    {/block}
{/block}