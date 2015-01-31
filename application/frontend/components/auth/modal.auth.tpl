{**
 * Модальное окно с формами входа, регистрации и напоминанием пароля
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$id = 'modal-login'}
    {$mods = "$mods auth"}
    {$classes = "$classes js-modal-default"}
    {$title = $aLang.auth.authorization}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
{/block}

{block 'modal_content'}
    {if ! Config::Get('general.reg.invite')}
        {component 'auth' template='registration' assign=auth_tab_reg}
    {else}
        {component 'auth' template='invite' assign=auth_tab_reg}
    {/if}

    {component 'auth' template='login' assign=auth_tab_login}
    {component 'auth' template='reset' assign=auth_tab_reset}

    {component 'tabs' classes='js-tabs-auth' tabs=[
        [ 'text' => {lang 'auth.login.title'},        'content' => $auth_tab_login, 'classes' => 'js-auth-tab-login' ],
        [ 'text' => {lang 'auth.registration.title'}, 'content' => $auth_tab_reg,   'classes' => 'js-auth-tab-reg' ],
        [ 'text' => {lang 'auth.reset.title'},        'content' => $auth_tab_reset ]
    ]}
{/block}

{block 'modal_footer'}{/block}