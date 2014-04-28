{**
 * Модальное окно с формами входа, регистрации и напоминанием пароля
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-login{/block}
{block name='modal_class'}modal-login js-modal-default{/block}
{block name='modal_title'}{$aLang.user_authorization}{/block}
{block name='modal_attributes'}data-modal-center="false"{/block}

{block name='modal_content'}
	{include 'components/nav/nav.tabs.tpl' sName='block_tags' sActiveItem='all' sMods='pills' sClasses='' aItems=[
		[ 'name' => 'login',        'text' => $aLang.user_login_submit,  'pane' => 'tab-pane-login' ],
		[ 'name' => 'registration', 'text' => $aLang.registration,       'pane' => 'tab-pane-registration' ],
		[ 'name' => 'reminder',     'text' => $aLang.password_reminder,  'pane' => 'tab-pane-reminder' ]
	]}

	<div data-type="tab-panes">
		<div class="tab-pane" id="tab-pane-login" data-type="tab-pane">
			{include file='forms/form.auth.login.tpl' isModal=true}
		</div>

		<div class="tab-pane" id="tab-pane-registration" data-type="tab-pane">
			{if ! $oConfig->GetValue('general.reg.invite')}
				{include file='forms/form.auth.signup.tpl' isModal=true}
			{else}
				{include file='forms/form.auth.invite.tpl' isModal=true}
			{/if}
		</div>

		<div class="tab-pane" id="tab-pane-reminder" data-type="tab-pane">
			{include file='forms/form.auth.recovery.tpl' isModal=true}
		</div>
	</div>
{/block}

{block name='modal_footer'}{/block}