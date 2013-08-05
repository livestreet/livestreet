<a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a> has invited you to register at <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a><br>
Your invitation code:  <b>{$oInvite->getCode()}</b><br>
To register, you need to enter the invitation code into the corresponding field on <a href="{router page='login'}"> the main page</a>. Please note, the invitation code is to be entered just once. After you have registered, use your login and password to access the website.												
<br><br>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>
							