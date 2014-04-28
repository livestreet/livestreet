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

		{include file='forms/fields/form.field.select.tpl'
			sFieldName          = 'type'
			sFieldLabel         = $aLang.user_complaint_type_title
			sFieldClasses       = 'width-full'
			aFieldItems         = $aTypes }

		{include file='forms/fields/form.field.textarea.tpl'
			sFieldName    = 'text'
			iFieldRows    = 5
			sFieldLabel   = $aLang.user_complaint_text_title
			sFieldClasses = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{include file='forms/fields/form.field.captcha.tpl'
			sCaptchaName   = 'complaint_user'
			sFieldName   = 'captcha'
			sFieldLabel  = $aLang.registration_captcha}
		{/if}

		{include file='forms/fields/form.field.hidden.tpl' sFieldName='user_id' sFieldValue=$_aRequest.user_id}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="ls.user.addComplaint('#form-complaint-user');">{$aLang.user_complaint_title}</button>
{/block}