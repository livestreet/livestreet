{**
 * Форма регистрации
 *
 * @param string $redirectUrl
 *}

{component_define_params params=[ 'redirectUrl' ]}

{$redirectUrl = $redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='registration_begin'}

<form action="{router page='auth/register'}" method="post" class="js-form-validate js-auth-registration-form">
    {hook run='form_registration_begin'}

    {* Логин *}
    {component 'field' template='text'
        name   = 'login'
        rules  = [ 'required' => true, 'minlength' => '3', 'remote' => "{router page='auth'}ajax-validate-login" ]
        label  = $aLang.auth.labels.login}

    {* E-mail *}
    {component 'field' template='email' rules=[ 'remote' => "{router page='auth'}ajax-validate-email" ]}

    {* Пароль *}
    {component 'field' template='text'
        name         = 'password'
        type         = 'password'
        rules        = [ 'required' => true, 'minlength' => '5', 'trigger' => 'input' ]
        label        = $aLang.auth.labels.password
        inputClasses = 'js-input-password-reg'}

    {* Повторите пароль *}
    {component 'field' template='text'
        name   = 'password_confirm'
        type   = 'password'
        rules  = [ 'required' => true, 'minlength' => '5', 'trigger' => 'input', 'equalto' => '.js-input-password-reg', 'equalto-message' => {lang 'auth.registration.notices.error_password_equal'} ]
        label  = $aLang.auth.registration.form.fields.password_confirm.label}

    {* Каптча *}
    {if Config::Get('module.user.captcha_use_registration')}
        {component 'field' template='captcha'
            captchaType = Config::Get('sys.captcha.type')
            captchaName = 'user_signup'
            name        = 'captcha'
            label       = $aLang.auth.labels.captcha}
    {/if}

    {hook run='form_registration_end'}

    {if $redirectUrl}
        {component 'field' template='hidden' name='return-path' value=$redirectUrl}
    {/if}

    {component 'button' name='submit_register' mods='primary' text=$aLang.auth.registration.form.fields.submit.text}
</form>

{hook run='registration_end'}
