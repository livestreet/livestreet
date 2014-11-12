{**
 * Жалоба на пользователя
 *
 * @param array $types
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-complaint-user{/block}
{block 'modal_class'}modal-complaint-user js-modal-default{/block}
{block 'modal_title'}{lang 'report.form.title'}{/block}

{block 'modal_content'}
	<form action="" method="post" id="form-complaint-user">
		{include 'components/field/field.select.tpl'
			name    = 'type'
			label   = {lang 'report.form.fields.type.label'}
			classes = 'width-full'
			items   = $smarty.local.types}

		{include 'components/field/field.textarea.tpl'
			name    = 'text'
			rows    = 5
			label   = {lang 'report.form.fields.text.label'}
			classes = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{include 'components/field/field.captcha.tpl'
				captchaName = 'complaint_user'
				name        = 'captcha'}
		{/if}

		{include 'components/field/field.hidden.tpl' name='target_id' value=$_aRequest.target_id}
	</form>
{/block}

{block 'modal_footer_begin'}
	{include 'components/button/button.tpl' text={lang 'report.form.submit'} mods='primary' form='form-complaint-user'}
{/block}