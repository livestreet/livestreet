{**
 * Список пользователей
 *
 * @param array   $users
 * @param boolean $selectable
 * @param string  $target
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = "modal-users-select"}
    {$mods = "$mods users-select"}
    {$classes = "$classes js-modal-default"}
    {$title = $title|default:$aLang.user.users|escape}
{/block}

{block 'modal_content'}
    {* Экшнбар *}
    {if $smarty.local.users && $smarty.local.selectable}
        {component 'actionbar' template='item.select'
            classes = 'js-user-list-modal-actionbar'
            target  = '.js-user-list-select .js-user-list-small-item'
            assign  = users}

        {component 'actionbar' items=[
            [ 'html' => $users ]
        ]}
    {/if}

    {* Список *}
    {component 'user' template='list-small'
        users      = $smarty.local.users
        selectable = $smarty.local.selectable
        showEmpty  = true
        classes    = 'js-user-list-select'}
{/block}

{block 'modal_footer_begin'}
    {if $smarty.local.users && $smarty.local.selectable}
        {component 'button'
            text       = $aLang.common.add
            mods       = 'primary'
            classes    = 'js-user-list-select-add'
            attributes = [ 'data-target' => $smarty.local.target ]}
    {/if}
{/block}