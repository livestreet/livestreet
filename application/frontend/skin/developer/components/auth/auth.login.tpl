{**
 * Форма входа
 *}

{$redirectUrl = $smarty.local.redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='login_begin'}

<form action="{router page='login'}" method="post" class="js-auth-login-form">
    {hook run='form_login_begin'}

    {* Логин *}
    {include 'components/field/field.text.tpl'
        name   = 'login'
        rules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
        label  = $aLang.auth.login.form.fields.login.label}

    {* Пароль *}
    {include 'components/field/field.text.tpl'
        name   = 'password'
        type   = 'password'
        rules  = [ 'required' => true, 'rangelength' => '[2,20]' ]
        label  = $aLang.auth.labels.password}

    {* Каптча *}
    {if Config::Get('general.login.captcha')}
        {include 'components/field/field.captcha.tpl'
            name   = 'captcha'
            captchaName   = 'user_auth'
            label  = $aLang.auth.labels.captcha}
    {/if}

    {* Запомнить *}
    {include 'components/field/field.checkbox.tpl'
        name    = 'remember'
        label   = $aLang.auth.login.form.fields.remember.label
        checked = true}

    {hook run='form_login_end'}

    {if $redirectUrl}
        {include 'components/field/field.hidden.tpl' name='return-path' value=$redirectUrl}
    {/if}

    {include 'components/button/button.tpl' name='submit_login' mods='primary' text=$aLang.auth.login.form.fields.submit.text}
</form>

{if $smarty.local.showExtra}
    <div class="pt-20">
        <a href="{router page='registration'}">{$aLang.auth.registration.title}</a><br />
        <a href="{router page='login'}reset/">{$aLang.auth.reset.title}</a>
    </div>
{/if}

{hook run='login_end'}