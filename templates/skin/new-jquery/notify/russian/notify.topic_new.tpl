Пользователь <a href="{$oUserTopic->getUserWebPath()}">{$oUserTopic->getLogin()}</a> опубликовал в блоге <b>«{$oBlog->getTitle()|escape:'html'}»</b> новый топик - <a href="{router page='blog'}{$oTopic->getId()}.html">{$oTopic->getTitle()|escape:'html'}</a><br>

<br><br>
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>