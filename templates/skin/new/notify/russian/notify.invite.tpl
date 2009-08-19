Пользователь <a href="{router page='profile'}{$oUserFrom->getLogin()}/">{$oUserFrom->getLogin()}</a>  пригласил вас зарегистрироваться на сайте <a href="{$aConfig.path.root.web}">{$aConfig.view.name}</a><br>
Код приглашения:  <b>{$oInvite->getCode()}</b><br>
Для регистрации вам будет необходимо ввести код приглашения на <a href="{router page='login'}">странице входа</a>													
<br><br>
С уважением, администрация сайта <a href="{$aConfig.path.root.web}">{$aConfig.view.name}</a>
							