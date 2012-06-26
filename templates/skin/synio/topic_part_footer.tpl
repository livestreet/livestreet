	{assign var="oBlog" value=$oTopic->getBlog()}
	{assign var="oUser" value=$oTopic->getUser()}
	{assign var="oVote" value=$oTopic->getVote()}
	{assign var="oFavourite" value=$oTopic->getFavourite()}


	<footer class="topic-footer">
		<ul class="topic-tags js-favourite-insert-after-form js-favourite-tags-topic-{$oTopic->getId()}">
			<li><i class="icon-synio-tags"></i></li>
			
			{strip}
				{if $oTopic->getTagsArray()}
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li>{if !$smarty.foreach.tags_list.first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
					{/foreach}
				{else}
					<li>{$aLang.topic_tags_empty}</li>
				{/if}
				
				{if $oUserCurrent}
					{if $oFavourite}
						{foreach from=$oFavourite->getTagsArray() item=sTag name=tags_list_user}
							<li class="topic-tags-user js-favourite-tag-user">, <a rel="tag" href="{$oUserCurrent->getUserWebPath()}favourites/topics/tag/{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
						{/foreach}
					{/if}
					
					<li class="topic-tags-edit js-favourite-tag-edit" {if !$oFavourite}style="display:none;"{/if}>
						<a href="#" onclick="return ls.favourite.showEditTags({$oTopic->getId()},'topic',this);" class="link-dotted">{$aLang.favourite_form_tags_button_show}</a>
					</li>
				{/if}
			{/strip}
		</ul>
		
		
		<div class="topic-share" id="topic_share_{$oTopic->getId()}">
			{hookb run="topic_share" topic=$oTopic bTopicList=$bTopicList}
				<div class="yashare-auto-init" data-yashareTitle="{$oTopic->getTitle()|escape:'html'}" data-yashareLink="{$oTopic->getUrl()}" data-yashareL10n="ru" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,gplus"></div>
			{/hookb}
			<div class="arrow"></div>
		</div>


		<ul class="topic-info">
			<li class="topic-info-author">
				<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
				<a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
			</li>
			<li class="topic-info-date">
				<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
					{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}
				</time>
			</li>
			<li class="topic-info-share" data-topic-id="{$oTopic->getId()}" onclick="jQuery('#topic_share_{$oTopic->getId()}').slideToggle(); return false;"><i class="icon-synio-share-blue" title="{$aLang.topic_share}"></i></li>
			
			<li class="topic-info-favourite" onclick="return ls.favourite.toggle({$oTopic->getId()},$('#fav_topic_{$oTopic->getId()}'),'topic');">
				<i id="fav_topic_{$oTopic->getId()}" class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}"></i>
				<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}">{$oTopic->getCountFavourite()}</span>
			</li>
		
			{if $bTopicList}
				<li class="topic-info-comments">
					{if $oTopic->getCountCommentNew()}
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}" class="new">
							<i class="icon-synio-comments-green-filled"></i>
							{$oTopic->getCountComment()}
						</a>
						<span>+{$oTopic->getCountCommentNew()}</span>
					{else}
						<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">
							{if $oTopic->getCountComment()}
								<i class="icon-synio-comments-green-filled"></i>
							{else}
								<i class="icon-synio-comments-blue"></i>
							{/if}
							
							{$oTopic->getCountComment()}
						</a>
					{/if}
				</li>
			{/if}
			
			<li class="topic-info-vote">
				<div id="vote_area_topic_{$oTopic->getId()}" class="vote-topic
																	{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
																		{if $oTopic->getRating() > 0}
																			vote-count-positive
																		{elseif $oTopic->getRating() < 0}
																			vote-count-negative
																		{elseif $oTopic->getRating() == 0}
																			vote-count-zero
																		{/if}
																	{/if}
																	
																	{if !$oUserCurrent or ($oUserCurrent && $oTopic->getUserId() != $oUserCurrent->getId())}
																		vote-not-self
																	{/if}
																	
																	{if $oVote} 
																		voted
																		
																		{if $oVote->getDirection() > 0}
																			voted-up
																		{elseif $oVote->getDirection() < 0}
																			voted-down
																		{elseif $oVote->getDirection() == 0}
																			voted-zero
																		{/if}
																	{else}
																		not-voted
																	{/if}
																	
																	{if (strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time') && !$oVote) || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId())}
																		vote-nobuttons
																	{/if}
																	
																	{if strtotime($oTopic->getDateAdd()) > $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
																		vote-not-expired
																	{/if}">
					{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
						{assign var="bVoteInfoShow" value=true}
					{/if}
					<div class="vote-item vote-down" onclick="return ls.vote.vote({$oTopic->getId()},this,-1,'topic');"><span><i></i></span></div>
					<div class="vote-item vote-count" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">
						<span id="vote_total_topic_{$oTopic->getId()}" {if $bVoteInfoShow}class="js-infobox-vote-topic"{/if}>
						{if $bVoteInfoShow}
							{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
						{else}
							<i onclick="return ls.vote.vote({$oTopic->getId()},this,0,'topic');"></i>
						{/if}
						</span>
					</div>
					<div class="vote-item vote-up" onclick="return ls.vote.vote({$oTopic->getId()},this,1,'topic');"><span><i></i></span></div>
					{if $bVoteInfoShow}
						<div id="vote-info-topic-{$oTopic->getId()}" style="display: none;">
							<ul class="vote-topic-info">
								<li><i class="icon-synio-vote-info-up"></i> {$oTopic->getCountVoteUp()}</li>
								<li><i class="icon-synio-vote-info-down"></i> {$oTopic->getCountVoteDown()}</li>
								<li><i class="icon-synio-vote-info-zero"></i> {$oTopic->getCountVoteAbstain()}</li>
								{hook run='topic_show_vote_stats' topic=$oTopic}
							</ul>
						</div>
					{/if}
				</div>
			</li>
			
			{hook run='topic_show_info' topic=$oTopic}
		</ul>

		
		{if !$bTopicList}
			{hook run='topic_show_end' topic=$oTopic}
		{/if}
	</footer>
</article> <!-- /.topic -->