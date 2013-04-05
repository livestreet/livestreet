The user <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> has replied to your post on <a href="{$oUserWall->getUserWebPath()}wall/"> the wall</a><br/>

Your post was: <i>{$oWallParent->getText()}</i><br/><br/>
Their reply reads as follows: <i>{$oWall->getText()}</i>

<br/><br/>
Best regards, 
<br>
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>