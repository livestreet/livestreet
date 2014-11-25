{**
 * Список пользователей
 *
 * @param array   $users
 * @param boolean $selectable
 * @param string  $target
 * @param string  $titleText
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-users-select{/block}

{block 'modal_class'}
    modal-users-select js-modal-default
{/block}

{block 'modal_title'}
    {$smarty.local.titleText|default:$aLang.user.users}
{/block}

{block 'modal_content'}
    {* Экшнбар *}
    {if $smarty.local.users && $smarty.local.selectable}
        {include 'components/actionbar/actionbar-item.select.tpl'
            classes = 'js-user-list-modal-actionbar'
            target  = '.js-user-list-select .js-user-list-small-item'
            assign  = users}

        {include 'components/actionbar/actionbar.tpl' items=[
            [ 'html' => $users ]
        ]}
    {/if}

    {* Список *}
    {include 'components/user/user-list-small.tpl'
        users      = $smarty.local.users
        selectable = $smarty.local.selectable
        showEmpty  = true
        classes    = 'js-user-list-select'}
{/block}

{block 'modal_footer_begin'}
    {if $smarty.local.users && $smarty.local.selectable}
        {include 'components/button/button.tpl'
            text       = $aLang.common.add
            mods       = 'primary'
            classes    = 'js-user-list-select-add'
            attributes = [ 'data-target' => $smarty.local.target ]}
    {/if}
{/block}