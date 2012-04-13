{foreach from=$aReplyWall item=oReplyWall}
	{assign var="oReplyUser" value=$oReplyWall->getUser()}
	<div id="wall-reply-item-{$oReplyWall->getId()}" class="js-wall-reply-item comment wall-comment-reply">
		<a href="{$oReplyUser->getUserWebPath()}"><img src="{$oReplyUser->getProfileAvatarPath(48)}" alt="avatar" class="comment-avatar" /></a>
		
		<ul class="comment-info clearfix">
			<li class="comment-author"><a href="{$oReplyUser->getUserWebPath()}">{$oReplyUser->getLogin()}</a></li>
			<li class="comment-date"><time datetime="{date_format date=$oReplyWall->getDateAdd() format='c'}">{date_format date=$oReplyWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time></li>
		</ul>

		{if $oReplyWall->isAllowDelete()}
			<a href="#" onclick="return ls.wall.remove({$oReplyWall->getId()});">Удалить</a>
		{/if}

		<div class="comment-content text">
			{$oReplyWall->getText()}
		</div>
	</div>
{/foreach}