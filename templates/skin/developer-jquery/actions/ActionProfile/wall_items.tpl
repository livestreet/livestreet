{foreach from=$aWall item=oWall}
	{assign var="oWallUser" value=$oWall->getUser()}
	{assign var="aReplyWall" value=$oWall->getLastReplyWall()}
	<div>
	{$oWallUser->getLogin()} {date_format date=$oWall->getDateAdd() format="j F Y, H:i"}
		<br>
	{$oWall->getText()} - ({$oWall->getId()})
		<br>
		<a href="#">Ответить</a>
		<div style="border: 1px solid red;">
		{if $aReplyWall}
			{include file='actions/ActionProfile/wall_items_reply.tpl'}
		{/if}
		</div>
	</div>
{/foreach}