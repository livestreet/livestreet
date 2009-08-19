Вы зарегистрировались на сайте <a href="{$aConfig.path.root.web}">{$aConfig.view.name}</a><br>
Ваши регистрационные данные:<br>
&nbsp;&nbsp;&nbsp;логин: <b>{$oUser->getLogin()}</b><br>
&nbsp;&nbsp;&nbsp;пароль: <b>{$sPassword}</b><br>
<br>
Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
<a href="{$aConfig.path.root.web}/registration/activate/{$oUser->getActivateKey()}/">{$aConfig.path.root.web}/registration/activate/{$oUser->getActivateKey()}/</a>

<br><br>
С уважением, администрация сайта <a href="{$aConfig.path.root.web}">{$aConfig.view.name}</a>