Thank you for registering at <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>!<br><br>
Your access details are:<br>
&nbsp;&nbsp;&nbsp;login: <b>{$oUser->getLogin()}</b><br>
&nbsp;&nbsp;&nbsp;password: <b>{$sPassword}</b><br>
<br>
To complete registration, you need to activate your account by clicking the below link: 
<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>

<br><br>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>