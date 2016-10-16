{**
 * Форма входа
 *
 * @param string $redirectUrl
 * @param boolean $showExtra
 *}

{component_define_params params=[ 'redirectUrl', 'showExtra' ]}

{$redirectUrl = $redirectUrl|default:$PATH_WEB_CURRENT}

{hook run='login_begin'}

<form action="{router page='auth/login'}" method="post" class="js-form-validate js-auth-login-form">
    {hook run='form_login_begin'}

    {* Логин *}
    {component 'field' template='text'
        name   = 'login'
        rules  = [ 'required' => true, 'minlength' => '3' ]
        label  = $aLang.auth.login.form.fields.login.label}

    {* Пароль *}
    {component 'field' template='text'
        name   = 'password'
        type   = 'password'
        rules  = [ 'required' => true, 'minlength' => '2' ]
        label  = $aLang.auth.labels.password}

    {* Каптча *}
    {if Config::Get('general.login.captcha')}
        {component 'field' template='captcha'
            captchaType = Config::Get('sys.captcha.type')
            captchaName = 'user_auth'
            name        = 'captcha'
            label       = $aLang.auth.labels.captcha}
    {/if}

    {* Запомнить *}
    {component 'field' template='checkbox'
        name    = 'remember'
        label   = $aLang.auth.login.form.fields.remember.label
        checked = true}

    {hook run='form_login_end'}

    {if $redirectUrl}
        {component 'field' template='hidden' name='return-path' value=$redirectUrl}
    {/if}

    {component 'button' name='submit_login' mods='primary' text=$aLang.auth.login.form.fields.submit.text}
</form>

{if $showExtra}
    <div class="ls-pt-20">
        <a href="{router page='auth/register'}">{$aLang.auth.registration.title}</a><br />
        <a href="{router page='auth/password-reset'}">{$aLang.auth.reset.title}</a>
    </div>
{/if}

{hook run='login_end'}