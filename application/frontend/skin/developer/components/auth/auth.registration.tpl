{**
 * Форма регистрации
 *
 * @param string $redirectUrl
 *}

{$redirectUrl = $smarty.local.redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='registration_begin'}

<form action="{router page='registration'}" method="post" class="js-auth-registration-form">
    {hook run='form_registration_begin'}

    {* Логин *}
    {include 'components/field/field.text.tpl'
        name   = 'login'
        rules  = [ 'required' => true, 'rangelength' => '[2,20]', 'remote' => "{router page='registration'}ajax-validate-fields", 'remote-method' => 'POST' ]
        label  = $aLang.auth.labels.login}

    {* E-mail *}
    {include 'components/field/field.email.tpl' rules=[ 'remote' => "{router page='registration'}ajax-validate-fields", 'remote-method' => 'POST' ]}

    {* Пароль *}
    {include 'components/field/field.text.tpl'
        name         = 'password'
        type         = 'password'
        rules        = [ 'required' => true, 'rangelength' => '[5,20]' ]
        label        = $aLang.auth.labels.password
        inputClasses = 'js-input-password-reg'}

    {* Повторите пароль *}
    {include 'components/field/field.text.tpl'
        name   = 'password_confirm'
        type   = 'password'
        rules  = [ 'required' => true, 'rangelength' => '[5,20]', 'equalto' => '.js-input-password-reg' ]
        label  = $aLang.auth.registration.form.fields.password_confirm.label}

    {* Каптча *}
    {include 'components/field/field.captcha.tpl'
        name        = 'captcha'
        captchaName = 'user_signup'
        label       = $aLang.auth.labels.captcha}

    {hook run='form_registration_end'}

    {if $redirectUrl}
        {include 'components/field/field.hidden.tpl' name='return-path' value=$redirectUrl}
    {/if}

    {include 'components/button/button.tpl' name='submit_register' mods='primary' text=$aLang.auth.registration.form.fields.submit.text}
</form>

{hook run='registration_end'}
