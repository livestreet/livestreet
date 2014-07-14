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
	{include 'components/nav/nav.tabs.tpl' sName='block_tags' sActiveItem='all' sMods='pills' sClasses='' aItems=[
		[ 'name' => 'login',        'text' => $aLang.auth.login.title,        'pane' => 'tab-pane-login' ],
		[ 'name' => 'registration', 'text' => $aLang.auth.registration.title, 'pane' => 'tab-pane-registration' ],
		[ 'name' => 'reset',        'text' => $aLang.auth.reset.title,        'pane' => 'tab-pane-reset' ]
	]}

	<div data-type="tab-panes">
		<div class="tab-pane" id="tab-pane-login" data-type="tab-pane">
			{include 'components/auth/auth.login.tpl'}
		</div>

		<div class="tab-pane" id="tab-pane-registration" data-type="tab-pane">
			{if ! Config::Get('general.reg.invite')}
				{include 'components/auth/auth.registration.tpl'}
			{else}
				{include 'components/auth/auth.invite.tpl'}
			{/if}
		</div>

		<div class="tab-pane" id="tab-pane-reset" data-type="tab-pane">
			{include 'components/auth/auth.reset.tpl'}
		</div>
	</div>
{/block}

{block 'modal_footer'}{/block}