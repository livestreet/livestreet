	{assign var="oBlog" value=$oTopic->getBlog()}
	{assign var="oUser" value=$oTopic->getUser()}
	{assign var="oVote" value=$oTopic->getVote()}


	<footer class="topic-footer">	
		<ul class="actions">								   
			{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
				<li><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}" class="actions-edit">{$aLang.topic_edit}</a></li>
			{/if}
			
			{if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
				<li><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="actions-delete">{$aLang.topic_delete}</a></li>
			{/if}
		</ul>


		<ul class="topic-tags">
			<li>{$aLang.block_tags}:</li>
			{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
				<li><a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
			{/foreach}								 
		</ul>


		<ul class="topic-info">
			<li id="vote_area_topic_{$oTopic->getId()}" class="vote 
																{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}
																	{if $oTopic->getRating() > 0}
																		vote-count-positive
																	{elseif $oTopic->getRating() < 0}
																		vote-count-negative
																	{/if}
																{/if}
																
																{if $oVote} 
																	voted
																	
																	{if $oVote->getDirection() > 0}
																		voted-up
																	{elseif $oVote->getDirection() < 0}
																		voted-down
																	{/if}
																{/if}">
				<div class="vote-up" onclick="return ls.vote.vote({$oTopic->getId()},this,1,'topic');"></div>
				<div class="vote-count" id="vote_total_topic_{$oTopic->getId()}" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">
					{if $oVote || ($oUserCurrent && $oTopic->getUserId() == $oUserCurrent->getId()) || strtotime($oTopic->getDateAdd()) < $smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')} 
						{if $oTopic->getRating() > 0}+{/if}{$oTopic->getRating()}
					{else} 
						<a href="#" onclick="return ls.vote.vote({$oTopic->getId()},this,0,'topic');">?</a> 
					{/if}
				</div>
				<div href="#" class="vote-down" onclick="return ls.vote.vote({$oTopic->getId()},this,-1,'topic');"></div>
			</li>
			
			<li class="topic-info-author"><a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
			<li class="topic-info-favourite">
				<div onclick="return ls.favourite.toggle({$oTopic->getId()},this,'topic');" class="favourite {if $oUserCurrent && $oTopic->getIsFavourite()}active{/if}"></div>
				<span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}">{$oTopic->getCountFavourite()}</span>
			</li>
			
			{if $bTopicList}
				<li class="topic-info-comments">
					<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()} {$oTopic->getCountComment()|declension:$aLang.comment_declension:'russian'}</a>
					{if $oTopic->getCountCommentNew()}<span>+{$oTopic->getCountCommentNew()}</span>{/if}
				</li>
			{/if}
			
			{hook run='topic_show_info' topic=$oTopic}
		</ul>

		
		{if !$bTopicList}
			{hook run='topic_show_end' topic=$oTopic}
		{/if}
	</footer>
</article> <!-- /.topic -->