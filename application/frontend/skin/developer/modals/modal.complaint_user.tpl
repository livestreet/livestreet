{**
 * Жалоба на пользователя
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-complaint-user{/block}
{block name='modal_class'}modal-complaint-user js-modal-default{/block}
{block name='modal_title'}{$aLang.user_complaint_title}{/block}

{block name='modal_content'}
	<form action="" method="post" onsubmit="return false;" id="form-complaint-user">
		{foreach Config::Get('module.user.complaint_type') as $sType}
			{$aTypes[] = [
			'value' => $sType,
			'text' => $aLang.user_complaint_type_list.{$sType}
			]}
		{/foreach}

		{include file='components/field/field.select.tpl'
			sName          = 'type'
			sLabel         = $aLang.user_complaint_type_title
			sClasses       = 'width-full'
			aItems         = $aTypes }

		{include file='components/field/field.textarea.tpl'
			sName    = 'text'
			iRows    = 5
			sLabel   = $aLang.user_complaint_text_title
			sClasses = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{include file='components/field/field.captcha.tpl'
			sCaptchaName   = 'complaint_user'
			sName   = 'captcha'
			sLabel  = $aLang.registration_captcha}
		{/if}

		{include file='components/field/field.hidden.tpl' sName='user_id' sValue=$_aRequest.user_id}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="ls.user.addComplaint('#form-complaint-user');">{$aLang.user_complaint_title}</button>
{/block}