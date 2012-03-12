{foreach from=$aWall item=oWall}
	{assign var="oWallUser" value=$oWall->getUser()}
	{assign var="aReplyWall" value=$oWall->getLastReplyWall()}
	<div id="wall-item-{$oWall->getId()}" class="js-wall-item">
		{$oWallUser->getLogin()} {date_format date=$oWall->getDateAdd() format="j F Y, H:i"}
		<br>
		{$oWall->getText()} - ({$oWall->getId()})
		<br>
		<a href="#" id="wall-button-reply" onclick="return ls.wall.toggleReply({$oWall->getId()});">Ответить</a>

		<div id="wall-reply-contener-{$oWall->getId()}" style="border: 1px solid red;">
			{if $aReplyWall}
				{include file='actions/ActionProfile/wall_items_reply.tpl'}
			{/if}
		</div>

		{if count($aReplyWall) < $oWall->getCountReply()}
			<a href="#" onclick="return ls.wall.loadReplyNext({$oWall->getId()});" id="wall-reply-button-next-{$oWall->getId()}">Остальные ответы, еще <span id="wall-reply-count-next-{$oWall->getId()}">{$oWall->getCountReply()-count($aReplyWall)}</span></a>
		{/if}

	</div>
{/foreach}