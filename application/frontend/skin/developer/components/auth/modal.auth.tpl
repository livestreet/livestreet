{**
 * Модальное окно с формами входа, регистрации и напоминанием пароля
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-login{/block}
{block 'modal_class'}modal-login js-modal-default{/block}
{block 'modal_title'}{$aLang.auth.authorization}{/block}
{block 'modal_attributes'}data-modal-center="false"{/block}

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