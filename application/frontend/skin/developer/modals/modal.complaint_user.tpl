{**
 * Жалоба на пользователя
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-complaint-user{/block}
{block 'modal_class'}modal-complaint-user js-modal-default{/block}
{block 'modal_title'}{lang name='report.form.title'}{/block}

{block 'modal_content'}
	<form action="" method="post" onsubmit="return false;" id="form-complaint-user">
		{foreach Config::Get('module.user.complaint_type') as $sType}
			{$aTypes[] = [
				'value' => $sType,
				'text' => $aLang.report.type_list.{$sType}
			]}
		{/foreach}

		{include 'components/field/field.select.tpl'
			sName    = 'type'
			sLabel   = {lang name='report.form.fields.type.label'}
			sClasses = 'width-full'
			aItems   = $aTypes }

		{include 'components/field/field.textarea.tpl'
			sName    = 'text'
			iRows    = 5
			sLabel   = {lang name='report.form.fields.text.label'}
			sClasses = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{include 'components/field/field.captcha.tpl'
				sCaptchaName = 'complaint_user'
				sName        = 'captcha'}
		{/if}

		{include 'components/field/field.hidden.tpl' sName='user_id' sValue=$_aRequest.user_id}
	</form>
{/block}

{block 'modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="ls.user.addComplaint('#form-complaint-user');">{$aLang.user_complaint_title}</button>
{/block}