{**
 * Каптча
 *
 * @scripts <framework>/js/livestreet/captcha.js
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder' prepend}
    {hookb run="registration_captcha" isPopup=$isModal}
        <span {if ! $isModal}style="background-image: url({cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId});"{/if}
              class="form-auth-captcha js-form-auth-captcha"></span>
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