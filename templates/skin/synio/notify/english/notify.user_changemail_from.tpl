You have sent a request to change user email <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> at <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>.<br/><br/>
Old email: <b>{$oChangemail->getMailFrom()}</b><br/>
New email: <b>{$oChangemail->getMailTo()}</b><br/>


<br/>
To confirm the email change, please click here:
<a href="{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/">{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/</a>

<br/><br/>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>