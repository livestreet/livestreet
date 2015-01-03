{**
 * Жалоба на пользователя
 *
 * @param array $types
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = "modal-complaint-user"}
    {$mods = "$mods report"}
    {$classes = "$classes js-modal-default"}
    {$title = {lang 'report.form.title'}}
{/block}

{block 'modal_content'}
	<form action="" method="post" id="form-complaint-user">
		{component 'field' template='select'
			name    = 'type'
			label   = {lang 'report.form.fields.type.label'}
			classes = 'width-full'
			items   = $smarty.local.types}

		{component 'field' template='textarea'
			name    = 'text'
			rows    = 5
			label   = {lang 'report.form.fields.text.label'}
			classes = 'width-full'}

		{* Каптча *}
		{if Config::Get('module.user.complaint_captcha')}
			{component 'field' template='captcha'
				captchaName = 'complaint_user'
				name        = 'captcha'}
		{/if}

		{component 'field' template='hidden' name='target_id' value=$_aRequest.target_id}
	</form>
{/block}

{block 'modal_footer_begin'}
	{component 'button' text={lang 'report.form.submit'} mods='primary' form='form-complaint-user'}
{/block}