{**
 * Форма регистрации
 *
 * @param string $redirectUrl
 *}

{$redirectUrl = $smarty.local.redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='registration_begin'}

<form action="{router page='auth/register'}" method="post" class="js-form-validate js-auth-registration-form">
    {hook run='form_registration_begin'}

    {* Логин *}
    {component 'field' template='text'
        name   = 'login'
        rules  = [ 'required' => true, 'minlength' => '3', 'remote' => "{router page='auth'}ajax-validate-fields" ]
        label  = $aLang.auth.labels.login}

    {* E-mail *}
    {component 'field' template='email' rules=[ 'remote' => "{router page='auth'}ajax-validate-fields" ]}

    {* Пароль *}
    {component 'field' template='text'
        name         = 'password'
        type         = 'password'
        rules        = [ 'required' => true, 'minlength' => '5' ]
        label        = $aLang.auth.labels.password
        inputClasses = 'js-input-password-reg'}

    {* Повторите пароль *}
    {component 'field' template='text'
        name   = 'password_confirm'
        type   = 'password'
        rules  = [ 'required' => true, 'minlength' => '5', 'equalto' => '.js-input-password-reg' ]
        label  = $aLang.auth.registration.form.fields.password_confirm.label}

    {* Каптча *}
    {component 'field' template='captcha'
        type        = Config::Get('sys.captcha.type')
        name        = 'captcha'
        captchaName = 'user_signup'
        label       = $aLang.auth.labels.captcha}

    {hook run='form_registration_end'}

    {if $redirectUrl}
        {component 'field' template='hidden' name='return-path' value=$redirectUrl}
    {/if}

    {component 'button' name='submit_register' mods='primary' text=$aLang.auth.registration.form.fields.submit.text}
</form>

{hook run='registration_end'}
