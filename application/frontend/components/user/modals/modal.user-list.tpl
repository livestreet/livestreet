{**
 * Список пользователей
 *
 * @param array   $users
 * @param boolean $selectable
 *}

{component_define_params params=[ 'users', 'selectable' ]}

{capture 'modal_content'}
    {* Экшнбар *}
    {if $users && $selectable}
        {component 'actionbar' template='item.select'
            classes = 'js-user-list-modal-actionbar'
            target  = '.js-user-list-select .js-user-list-small-item'
            assign  = usersHtml}

        {component 'actionbar' items=[[ 'buttons' => [ 'html' => $usersHtml ] ]]}
    {/if}

    {* Список *}
    {component 'user' template='list-small'
        users      = $users
        selectable = $selectable
        showEmpty  = true
        classes    = 'js-user-list-select'}
{/capture}

{component 'modal'
    title         = $title|default:$aLang.user.users|escape
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'users-select'
    id            = 'modal-users-select'
    primaryButton  = ( $users && $selectable ) ? [
        'text'       => {lang 'common.add'},
        'classes'    => 'js-user-list-select-add',
        'form'       => 'form-complaint-user'
    ] : false}