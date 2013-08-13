You have sent a request to change user email <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> on site <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>.<br/>
Old email: <b>{$oChangemail->getMailFrom()}</b><br/>
New email: <b>{$oChangemail->getMailTo()}</b><br/>


<br/>
To confirm the email change, please click here:
<a href="{router page='profile'}changemail/confirm-to/{$oChangemail->getCodeTo()}/">{router page='profile'}changemail/confirm-to/{$oChangemail->getCodeTo()}/</a>

<br/><br/>
Best regards, site administration <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>