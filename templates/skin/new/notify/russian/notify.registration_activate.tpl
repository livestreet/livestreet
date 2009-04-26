Вы зарегистрировались на сайте <a href="{$DIR_WEB_ROOT}">{$SITE_NAME}</a><br>
Ваши регистрационные данные:<br>
&nbsp;&nbsp;&nbsp;логин: <b>{$oUser->getLogin()}</b><br>
&nbsp;&nbsp;&nbsp;пароль: <b>{$sPassword}</b><br>
<br>
Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
<a href="{$DIR_WEB_ROOT}/registration/activate/{$oUser->getActivateKey()}/">{$DIR_WEB_ROOT}/registration/activate/{$oUser->getActivateKey()}/</a>

<br><br>
С уважением, администрация сайта <a href="{$DIR_WEB_ROOT}">{$SITE_NAME}</a>