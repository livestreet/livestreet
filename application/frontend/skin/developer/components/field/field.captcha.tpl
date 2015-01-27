{**
 * Каптча
 *
 * @scripts <framework>/js/livestreet/captcha.js
 *}

{extends './field.text.tpl'}

{block 'field_input' prepend}
	<span data-type="captcha" data-lscaptcha-name="{$smarty.local.captchaName}" class="field--captcha-image"></span>

	{$_rules = [
		'required'          => true,
		'remote'            => {router page='ajax/captcha/validate'},
		'remote-method'     => 'POST',
		'remote-param-name' => $smarty.local.captchaName
	]}

	{$_inputClasses = "$_inputClasses width-100"}
{/block}
