{extends 'Component@user.user-list-small-item'}

{block 'user_list_small_item_options' append}
    {block 'user_list_add_item_options'}{/block}
{/block}

{block 'user_list_small_item_content' append}
    {block 'user_list_add_item_content'}
        {* Действия *}
        {if $smarty.local.showActions}
            <ul class="{$component}-actions js-user-list-small-actions">
                {block 'user_list_add_item_actions'}
                    {if $smarty.local.showRemove|default:true}
                        <li class="icon-remove js-user-list-add-user-remove" title="{$aLang.common.remove}" data-user-id="{$userId}"></li>
                    {/if}
                {/block}
            </ul>
        {/if}
    {/block}
{/block}