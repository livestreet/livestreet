{**
 * Каптча
 *
 * @scripts <framework>/js/livestreet/captcha.js
 *}

{extends file='forms/fields/form.field.base.tpl'}

{block name='field_holder' prepend}
	{hookb run="captcha"}
		<span style="background-image: url({cfg name='path.framework.libs_vendor.web'}/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n={rand()}&name={$sCaptchaName});" data-type="captcha" data-captcha-name="{$sCaptchaName}" class="form-auth-captcha"></span>
		<input type="text"
			   id="{if $sFieldId}{$sFieldId}{/if}"
			   name="{$sFieldName}"
			   value="{if $sFieldValue}{$sFieldValue}{elseif $_aRequest[$sFieldName]}{$_aRequest[$sFieldName]}{/if}"
			   class="{if $sFieldClasses}{$sFieldClasses}{else}width-150{/if} js-input-{$sFieldName}"
			   data-required="true"
			   data-remote="{router page='ajax/validate/captcha'}"
			   data-remote-method="POST"
			   data-remote-param-name="{$sCaptchaName}"
			   {if $bFieldIsDisabled}disabled{/if} />
	{/hookb}
{/block}
