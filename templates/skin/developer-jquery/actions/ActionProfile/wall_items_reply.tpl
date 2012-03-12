{foreach from=$aReplyWall item=oReplyWall}
	{assign var="oReplyUser" value=$oReplyWall->getUser()}
	<div id="wall-reply-item-{$oReplyWall->getId()}" class="js-wall-reply-item">
		{$oReplyUser->getLogin()} {date_format date=$oReplyWall->getDateAdd() format="j F Y, H:i"}
		<br>
		{$oReplyWall->getText()} - ({$oReplyWall->getId()})
	</div>
{/foreach}