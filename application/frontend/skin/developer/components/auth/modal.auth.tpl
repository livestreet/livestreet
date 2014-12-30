{**
 * Модальное окно с формами входа, регистрации и напоминанием пароля
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = 'modal-login'}
    {$mods = "$mods auth"}
    {$classes = "$classes js-modal-default"}
    {$title = $aLang.auth.authorization}
    {$attributes = array_merge( $attributes|default:[], [ 'data-modal-center' => 'false' ] )}
{/block}

{block 'modal_content'}
    {if ! Config::Get('general.reg.invite')}
        {include 'components/auth/auth.registration.tpl' assign=auth_tab_reg}
    {else}
        {include 'components/auth/auth.invite.tpl' assign=auth_tab_reg}
    {/if}

    {include 'components/auth/auth.login.tpl' assign=auth_tab_login}
    {include 'components/auth/auth.reset.tpl' assign=auth_tab_reset}

    {include 'components/tabs/tabs.tpl' classes='js-tabs-auth' tabs=[
        [ 'text' => {lang 'auth.login.title'},        'content' => $auth_tab_login, 'classes' => 'js-auth-tab-login' ],
        [ 'text' => {lang 'auth.registration.title'}, 'content' => $auth_tab_reg,   'classes' => 'js-auth-tab-reg' ],
        [ 'text' => {lang 'auth.reset.title'},        'content' => $auth_tab_reset ]
    ]}
{/block}

{block 'modal_footer'}{/block}