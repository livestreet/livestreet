{**
 * Модальное окно с формами входа, регистрации и напоминанием пароля
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-login{/block}
{block name='modal_class'}modal-login js-modal-default{/block}
{block name='modal_title'}{$aLang.user_authorization}{/block}

{block name='modal_content'}
	<ul class="nav nav-pills nav-pills-tabs" data-type="tabs">
		<li data-type="tab" data-option-target="tab-pane-login"><a href="#">{$aLang.user_login_submit}</a></li>
		<li data-type="tab" data-option-target="tab-pane-registration"><a href="#">{$aLang.registration}</a></li>
		<li data-type="tab" data-option-target="tab-pane-reminder"><a href="#">{$aLang.password_reminder}</a></li>
	</ul>
	
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