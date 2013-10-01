{**
 * Каптча
 *
 * @scripts <framework>/js/livestreet/captcha.js
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder' prepend}
    {hookb run="registration_captcha" isPopup=$isModal}
        {if ! $isModal}
            <img src="{cfg name='path.framework.libs_vendor.web'}/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}" class="form-auth-captcha js-form-auth-captcha"></span>
        {else}
            <span style="background-image: url({cfg name='path.framework.libs_vendor.web'}/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId});" class="form-auth-captcha js-form-auth-captcha"></span>
        {/if}
        <input type="text"
               id="{$sFieldName}"
               name="{$sFieldName}"
               value="{if $sFieldValue}{$sFieldValue}{else}{if $_aRequest[$sFieldName]}{$_aRequest[$sFieldName]}{/if}{/if}"
               class="{if $sFieldClasses}{$sFieldClasses}{else}width-150{/if} js-input-{$sFieldName}"
               data-length="3"
               data-required="true"
               data-remote="{router page='registration'}ajax-validate-fields"
               data-remote-method="POST"
               {if $bFieldIsDisabled}disabled{/if} />
    {/hookb}
{/block}
