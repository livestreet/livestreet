<div class="topic">
	<h2 class="title">
		<a href="{$oTopic->getBlogUrlFull()}" class="blog-title">{$oTopic->getBlogTitle()|escape:'html'}</a><span>&nbsp;/</span>
		{if $oTopic->getPublish()==0}<img src="{$DIR_STATIC_SKIN}/images/topic_unpublish.gif" title="{$aLang.topic_unpublish}" alt="{$aLang.topic_unpublish}">{/if}	
		{if $oTopic->getType()=='link'}<img src="{$DIR_STATIC_SKIN}/images/topic_link.gif" title="{$aLang.topic_link}" alt="{$aLang.topic_link}">{/if}				
		<a href="{if $oTopic->getType()=='link'}{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LINK}/go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}">{$oTopic->getTitle()|escape:'html'}</a>
	</h2>

	
	<div class="content">
		{if $oTopic->getType()=='question'}   			
			<div id="topic_question_area_{$oTopic->getId()}" class="poll">
				{if !$oTopic->getUserQuestionIsVote()} 		
					<ul>	
						{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}				
							<li><label for="topic_answer_{$oTopic->getId()}_{$key}"><input type="radio" id="topic_answer_{$oTopic->getId()}_{$key}" name="topic_answer_{$oTopic->getId()}" value="{$key}" onchange="$('topic_answer_{$oTopic->getId()}_value').setProperty('value',this.value);" /> {$aAnswer.text}</label></li>				
						{/foreach}
						<li>
							<input type="submit" value="{$aLang.topic_question_vote}" onclick="ajaxQuestionVote({$oTopic->getId()},$('topic_answer_{$oTopic->getId()}_value').getProperty('value'));" />
							<input type="submit" value="{$aLang.topic_question_abstain}" onclick="ajaxQuestionVote({$oTopic->getId()},-1)" />
						</li>							
					</ul>	
					<input type="hidden" id="topic_answer_{$oTopic->getId()}_value" value="-1" />								
					<span>{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br />		
				{else}			
					{include file='topic_question.tpl'}
				{/if}
			</div>	
		{/if}
			
			
		{$oTopic->getText()}
	</div>	

	
	{if $oUserCurrent}
		<ul class="action">									
			{if $oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oTopic->getUserIsBlogAdministrator() or $oTopic->getUserIsBlogModerator() or $oTopic->getBlogOwnerId()==$oUserCurrent->getId()}
				<li class="edit"><a href="{$DIR_WEB_ROOT}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}">{$aLang.topic_edit}</a></li>
			{/if}
			{if $oUserCurrent->isAdministrator() or $oTopic->getUserIsBlogAdministrator() or $oTopic->getBlogOwnerId()==$oUserCurrent->getId()}
				<li class="delete"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/delete/{$oTopic->getId()}/" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');">{$aLang.topic_delete}</a></li>
			{/if}
		</ul>
	{/if}

	
	<ul class="tags">
		{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TAG}/{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a></li>
		{/foreach}									
	</ul>	

	
	<ul class="info">
		<li class="voting {if $oTopic->getUserIsVote() || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$VOTE_LIMIT_TIME_TOPIC}{if $oTopic->getRating()>0}positive{elseif $oTopic->getRating()<0}negative{/if}{/if} {if !$oUserCurrent || $oTopic->getUserId()==$oUserCurrent->getId() || strtotime($oTopic->getDateAdd())<$smarty.now-$VOTE_LIMIT_TIME_TOPIC}guest{/if} {if $oTopic->getUserIsVote()} voted {if $oTopic->getUserVoteDelta()>0}plus{elseif $oTopic->getUserVoteDelta()<0}minus{/if}{/if}">
			<a href="#" class="plus" onclick="lsVote.vote({$oTopic->getId()},this,1,'topic'); return false;"></a>
			<span class="total" title="проголосовало: {$oTopic->getCountVote()}">{if $oTopic->getUserIsVote() || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$VOTE_LIMIT_TIME_TOPIC} {if $oTopic->getRating()>0}+{/if}{$oTopic->getRating()} {else} <a href="#" onclick="lsVote.vote({$oTopic->getId()},this,0,'topic'); return false;">&mdash;</a> {/if}</span>
			<a href="#" class="minus" onclick="lsVote.vote({$oTopic->getId()},this,-1,'topic'); return false;"></a>
		</li>
		<li class="date">{date_format date=$oTopic->getDateAdd()}</li>
		<li class="favorite {if $oUserCurrent}{if $oTopic->getIsFavourite()}active{/if}{else}fav-guest{/if}"><a href="#" onclick="lsFavourite.toggle({$oTopic->getId()},this,'topic'); return false;"></a></li>		
		{if $oTopic->getType()=='link'}<li class="link"><a href="{$DIR_WEB_ROOT}/link/go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl(true)}</a></li>						{/if}					
		<li class="author"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oTopic->getUserLogin()}/">{$oTopic->getUserLogin()}</a></li>		
		<li class="comments">
			{if $oTopic->getCountComment()>0}
				<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}"><span class="cm-total">{$oTopic->getCountComment()}</span>{if $oTopic->getCountCommentNew()}<span class="cm-new">+{$oTopic->getCountCommentNew()}</span>{/if}</a>
			{else}
				<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_add}"><span class="cm-total">{$aLang.topic_comment_add}</span></a>
			{/if}
		</li>			
	</ul>
</div>