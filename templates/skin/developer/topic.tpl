{assign var="oBlog" value=$oTopic->getBlog()}
{assign var="oUser" value=$oTopic->getUser()}
{assign var="oVote" value=$oTopic->getVote()} 


<div class="topic">
	<h2 class="title">
		<a href="{$oBlog->getUrlFull()}" class="title-blog">{$oBlog->getTitle()|escape:'html'}</a> &rarr;
		{if $oTopic->getPublish()==0}	
			<img src="{cfg name='path.static.skin'}/images/draft.png" title="{$aLang.topic_unpublish}" alt="{$aLang.topic_unpublish}" />
		{/if}
		{if $oTopic->getType() == 'link'}<img src="{cfg name='path.static.skin'}/images/topic_link.png" title="{$aLang.topic_link}" alt="{$aLang.topic_link}" />{/if}
		<a href="{if $oTopic->getType()=='link'}{router page='link'}go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}" class="title-topic">{$oTopic->getTitle()|escape:'html'}</a>
	</h2>
	
	
	
	<ul class="actions">									
		{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getUserIsModerator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
			<li><a href="{cfg name='path.root.web'}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}" class="edit">{$aLang.topic_edit}</a></li>
		{/if}
		{if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() or $oBlog->getOwnerId()==$oUserCurrent->getId())}
			<li><a href="{router page='topic'}delete/{$oTopic->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');" class="delete">{$aLang.topic_delete}</a></li>
		{/if}
	</ul>



	<div class="content">
		{if $oTopic->getType()=='question'}
			<div id="topic_question_area_{$oTopic->getId()}" class="poll">
				{if !$oTopic->getUserQuestionIsVote()}
					<ul class="poll-vote">
						{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}
							<li><label><input type="radio" id="topic_answer_{$oTopic->getId()}_{$key}" name="topic_answer_{$oTopic->getId()}" value="{$key}" onchange="$('topic_answer_{$oTopic->getId()}_value').setProperty('value',this.value);" /> {$aAnswer.text|escape:'html'}</label></li>
						{/foreach}
					</ul>

					<input type="submit" value="{$aLang.topic_question_vote}" onclick="ajaxQuestionVote({$oTopic->getId()},$('topic_answer_{$oTopic->getId()}_value').getProperty('value'));" />
					<input type="submit" value="{$aLang.topic_question_abstain}" onclick="ajaxQuestionVote({$oTopic->getId()},-1)" />
					<input type="hidden" id="topic_answer_{$oTopic->getId()}_value" value="-1" />
				{else}
					{include file='topic_question.tpl'}
				{/if}
			</div>
		{/if}

		{if !$tSingle}
			{$oTopic->getTextShort()}
			{if $oTopic->getTextShort()!=$oTopic->getText()}
				<a href="{$oTopic->getUrl()}#cut" title="{$aLang.topic_read_more}">
				{if $oTopic->getCutText()}
					{$oTopic->getCutText()}
				{else}
					{$aLang.topic_read_more}
				{/if}      			
				</a>
			{/if}
		{else}
			{$oTopic->getText()}
		{/if}
	</div>	



	<ul class="tags">
		{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
			<li><a href="{router page='tag'}{$sTag|escape:'html'}/">{$sTag|escape:'html'}</a>{if !$smarty.foreach.tags_list.last}, {/if}</li>
		{/foreach}								
	</ul>



	<ul class="info">
		<li class="voting {if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}{if $oTopic->getRating()>0}positive{elseif $oTopic->getRating()<0}negative{/if}{/if} {if !$oUserCurrent || $oTopic->getUserId()==$oUserCurrent->getId() || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')}guest{/if}{if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
			<a href="#" class="plus" onclick="lsVote.vote({$oTopic->getId()},this,1,'topic'); return false;"></a>
			<span class="total" title="{$aLang.topic_vote_count}: {$oTopic->getCountVote()}">{if $oVote || ($oUserCurrent && $oTopic->getUserId()==$oUserCurrent->getId()) || strtotime($oTopic->getDateAdd())<$smarty.now-$oConfig->GetValue('acl.vote.topic.limit_time')} {if $oTopic->getRating()>0}+{/if}{$oTopic->getRating()} {else} <a href="#" onclick="lsVote.vote({$oTopic->getId()},this,0,'topic'); return false;">&mdash;</a> {/if}</span>
			<a href="#" class="minus" onclick="lsVote.vote({$oTopic->getId()},this,-1,'topic'); return false;"></a>
		</li>
		<li class="username"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>	
		<li class="date">{date_format date=$oTopic->getDateAdd()}</li>
		<li><a href="#" onclick="lsFavourite.toggle({$oTopic->getId()},this,'topic'); return false;" class="favorite {if $oUserCurrent}{if $oTopic->getIsFavourite()}active{/if}{else}fav-guest{/if}"></a></li>
		{if $oTopic->getType()=='link'}
			<li><a href="{router page='link'}go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}">{$oTopic->getLinkUrl(true)}</a></li>
		{/if}
		{if !$tSingle}
			<li class="comments-link">
				{if $oTopic->getCountComment()>0}
					<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}">{$oTopic->getCountComment()} <span>{if $oTopic->getCountCommentNew()}+{$oTopic->getCountCommentNew()}{/if}</span></a>
				{else}
					<a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_add}">0</a>
				{/if}
			</li>
		{/if}
		{hook run='topic_show_info' topic=$oTopic}
	</ul>
	{if $tSingle}
		{hook run='topic_show_end' topic=$oTopic}
	{/if}
</div>