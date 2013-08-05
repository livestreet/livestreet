The user <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> has posted on <a href="{$oUserWall->getUserWebPath()}wall/">your wall</a><br/>

Their post reads as follows: <i>{$oWall->getText()}</i>

<br/><br/>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>