{**
 * Каптча
 *
 * @scripts <framework>/js/livestreet/captcha.js
 *}

{extends './field.text.tpl'}

{block 'field_input' prepend}
	<span data-type="captcha" data-captcha-name="{$smarty.local.sName}" class="form-auth-captcha"></span>

	{$_aRules = [
		'required'          => true,
		'remote'            => {router page='ajax/validate/captcha'},
		'remote-method'     => 'POST',
		'remote-param-name' => $smarty.local.sName
	]}

	{$_sInputClasses = "$_sInputClasses width-100"}
{/block}
