Вы зарегистрировались на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a><br>
Ваши регистрационные данные:<br>
&nbsp;&nbsp;&nbsp;логин: <b>{$oUser->getLogin()}</b><br>
&nbsp;&nbsp;&nbsp;пароль: <b>{$sPassword}</b><br>
<br>
Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>

<br><br>
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>