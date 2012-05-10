{foreach from=$aWall item=oWall}
	{assign var="oWallUser" value=$oWall->getUser()}
	{assign var="aReplyWall" value=$oWall->getLastReplyWall()}

	<div id="wall-item-{$oWall->getId()}" class="js-wall-item wall-item-wrapper">
		<div class="wall-item">
			<a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
			
			<p class="info">
				<a href="{$oWallUser->getUserWebPath()}">{$oWallUser->getLogin()}</a> ·
				<time class="date" datetime="{date_format date=$oWall->getDateAdd() format='c'}">{date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
			</p>

			<div class="wall-item-content text">
				{$oWall->getText()}
			</div>
			
			{if $oUserCurrent}
				<ul class="actions wall-item-actions">
					{if $oUserCurrent and !$aReplyWall}
						<li><a href="#" class="link-dotted" onclick="return ls.wall.toggleReply({$oWall->getId()});">{$aLang.wall_action_reply}</a></li>
					{/if}
					{if $oWall->isAllowDelete()}
						<li><a href="#" onclick="return ls.wall.remove({$oWall->getId()});" class="link-dotted">{$aLang.wall_action_delete}</a></li>
					{/if}
				</ul>
			{/if}
		</div>
		
		{if $aReplyWall}
			<div class="wall-item-replies" id="wall-item-replies-{$oWall->getId()}">
				{if count($aReplyWall) < $oWall->getCountReply()}
					<a href="#" onclick="return ls.wall.loadReplyNext({$oWall->getId()});" id="wall-reply-button-next-{$oWall->getId()}" class="wall-more-reply">
						<span class="wall-more-inner">{$aLang.wall_load_reply_more} <span id="wall-reply-count-next-{$oWall->getId()}">{$oWall->getCountReply()}</span> {$oWall->getCountReply()|declension:$aLang.comment_declension:'russian'}</span>
					</a>
				{/if}
			
				{if $aReplyWall}
					<div class="wall-item-container" id="wall-reply-container-{$oWall->getId()}">
						{include file='actions/ActionProfile/wall_items_reply.tpl'}
					</div>
				{/if}
			</div>
		{/if}
		
		{if $oUserCurrent}
			<form class="wall-submit wall-submit-reply" {if !$aReplyWall}style="display: none"{/if}>
				<textarea rows="4" id="wall-reply-text-{$oWall->getId()}" class="input-text input-width-full js-wall-reply-text" placeholder="{$aLang.wall_reply_placeholder}" onclick="return ls.wall.expandReply({$oWall->getId()});"></textarea>
				<button type="button" onclick="ls.wall.addReply(jQuery('#wall-reply-text-{$oWall->getId()}').val(), {$oWall->getId()});" class="button button-primary js-button-wall-submit">{$aLang.wall_reply_submit}</button>
			</form>
		{/if}
	</div>
{/foreach}