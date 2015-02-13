{**
 * Список пользователей
 *
 * @param array   $users
 * @param boolean $selectable
 * @param string  $target
 *}

{capture 'modal_content'}
    {* Экшнбар *}
    {if $smarty.local.users && $smarty.local.selectable}
        {component 'actionbar' template='item.select'
            classes = 'js-user-list-modal-actionbar'
            target  = '.js-user-list-select .js-user-list-small-item'
            assign  = users}

        {component 'actionbar' items=[[ 'buttons' => [ 'html' => $users ] ]]}
    {/if}

    {* Список *}
    {component 'user' template='list-small'
        users      = $smarty.local.users
        selectable = $smarty.local.selectable
        showEmpty  = true
        classes    = 'js-user-list-select'}
{/capture}

{component 'modal'
    title         = $title|default:$aLang.user.users|escape
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'users-select'
    id            = 'modal-users-select'
    primaryButton  = ( $smarty.local.users && $smarty.local.selectable ) ? [
        'text'       => {lang 'common.add'},
        'classes'    => 'js-user-list-select-add',
        'attributes' => [ 'data-target' => $smarty.local.target ],
        'form'       => 'form-complaint-user'
    ] : false}