{**
 * Жалоба на пользователя
 *
 * TODO: Универсализировать
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-complaint-user{/block}
{block 'modal_class'}modal-complaint-user js-modal-default{/block}
{block 'modal_title'}{lang name='report.form.title'}{/block}

{block 'modal_content'}
	<form action="" method="post" onsubmit="return false;" id="form-complaint-user">
		{foreach Config::Get('module.user.complaint_type') as $type}
			{$aTypes[] = [
				'value' => $type,
				'text' => $aLang.report.type_list.{$type}
			]}
		{/foreach}

		{include 'components/field/field.select.tpl'
			name    = 'type'
			label   = {lang name='report.form.fields.type.label'}
			classes = 'width-full'
			items   = $aTypes}

		{include 'components/field/field.textarea.tpl'
			name    = 'text'
			rows    = 5
			label   = {lang name='report.form.fields.text.label'}
			classes = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{include 'components/field/field.captcha.tpl'
				captchaName = 'complaint_user'
				name        = 'captcha'}
		{/if}

		{include 'components/field/field.hidden.tpl' name='user_id' value=$_aRequest.user_id}
	</form>
{/block}

{block 'modal_footer_begin'}
	{include 'components/button/button.tpl' text={lang 'report.form.submit'} mods='primary' form='form-complaint-user'}
{/block}