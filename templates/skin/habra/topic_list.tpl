<DIV class=blogposts>
	
{if count($aTopics)>0}	
	{foreach from=$aTopics item=oTopic}    
	<div class="entry_item">
 		<div class="text">
  			<h1 class="blog_headline">
  				<a href="{$oTopic->getBlogUrlFull()}" class="blog_headline_group">{$oTopic->getBlogTitle()|escape:'html'}</a>&nbsp;&#8594;&nbsp;
  				{if $oTopic->getType()=='link'}
  					<img src="{$DIR_STATIC_SKIN}/img/link_url_big.gif" border="0" title="{$aLang.topic_link}" width="16" height="16" alt="{$aLang.topic_link}">
  				{/if}
  				{if $oTopic->getPublish()==0}
  					<img src="{$DIR_STATIC_SKIN}/img/topic_unpublish.gif" border="0" title="{$aLang.topic_unpublish}">
  				{/if}
  				<a href="{if $oTopic->getType()=='link'}{$DIR_WEB_ROOT}/link/go/{$oTopic->getId()}/{else}{$oTopic->getUrl()}{/if}" class="headline_l">{$oTopic->getTitle()|escape:'html'}</a>
  				
  				{if $oUserCurrent and ($oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oTopic->getUserIsBlogAdministrator() or $oTopic->getUserIsBlogModerator() or $oTopic->getBlogOwnerId()==$oUserCurrent->getId())}
  					<a href="{$DIR_WEB_ROOT}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}"><img src="{$DIR_STATIC_SKIN}/img/blog_edit.gif" border="0" title="{$aLang.topic_edit}"></a>
  				{/if}
  				
  			</h1>
  			<div class="groups_topic_text">
  			
  			{if $oTopic->getType()=='question'}   
    		
    		<div class="poll" style="margin-top:20px;" id="topic_question_area_{$oTopic->getId()}">
    		{if !$oTopic->getUserQuestionIsVote()} 			
				{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}
					<input type="radio" name="topic_answer_{$oTopic->getId()}" value="{$key}" id="topic_answer_{$oTopic->getId()}_{$key}" onchange="document.getElementById('topic_answer_{$oTopic->getId()}_value').value=this.value;"> <label for="topic_answer_{$oTopic->getId()}_{$key}">{$aAnswer.text}</label> <br>
				{/foreach}
				<br>
				<input type="hidden" id="topic_answer_{$oTopic->getId()}_value" value="77">
				<input type="submit"  value="{$aLang.topic_question_vote}" onclick="ajaxQuestionVote({$oTopic->getId()},document.getElementById('topic_answer_{$oTopic->getId()}_value').value)">
				<input type="submit"  value="{$aLang.topic_question_abstain}"  onclick="ajaxQuestionVote({$oTopic->getId()},-1)">
				<br><br>
				<span class="total">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>			
			{else}							
				{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}			
					<dl>
					<dt><strong>{$oTopic->getQuestionAnswerPercent($key)}%</strong><br/>({$aAnswer.count})</dt>
					<dd>{$aAnswer.text}<br/><img width="{$oTopic->getQuestionAnswerPercent($key)}%" height="5" alt="" src="{$DIR_STATIC_SKIN}/img/vote_space.gif"/></dd>
					</dl>
				{/foreach}							
				<span class="total">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>
			{/if}
			</div>
			<br>	
						
    		{/if}
    		
      			{$oTopic->getTextShort()}
      			{if $oTopic->getTextShort()!=$oTopic->getText()}
      				<br><br>( <a href="{$oTopic->getUrl()}" title="{$aLang.topic_read_more}">
      				{if $oTopic->getCutText()}
      					{$oTopic->getCutText()}
      				{else}
      					{$aLang.topic_read_more}
      				{/if}      			
      				</a> )
      			{/if}
      			<div style="clear: left;"></div>
				<div class="posttags">
					{$oTopic->getTagsLink()}					
				</div>
   				<div class="info_holder">
     				<div class="ball first" id="voter1"> 
     				    					
     					<span id="topic_vote_self_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_self}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_self}" />
     					</span>
     					<span id="topic_vote_anonim_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_guest}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_guest}" />
     					</span>
     					<span id="topic_vote_is_vote_down_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_already}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_already}" />
     					</span>
     					<span id="topic_vote_is_vote_up_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_already}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_already}" />
     					</span>
     					<span id="topic_vote_ok_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<a href="#" onclick="ajaxVoteTopic({$oTopic->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_up}" /></a><a href="#" onclick="ajaxVoteTopic({$oTopic->getId()},-1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_down}" /></a>
     					</span>
     					
     					{if $oUserCurrent}
     						{if $oTopic->getUserId()==$oUserCurrent->getId()}
   								<script>showTopicVote('topic_vote_self',{$oTopic->getId()});</script>
   							{else}
   								{if $oTopic->getUserIsVote()}
   									{if $oTopic->getUserVoteDelta()>0}
   										<script>showTopicVote('topic_vote_is_vote_up',{$oTopic->getId()});</script>
   									{else}
   										<script>showTopicVote('topic_vote_is_vote_down',{$oTopic->getId()});</script>
   									{/if}
   								{else}
   									<script>showTopicVote('topic_vote_ok',{$oTopic->getId()});</script>
   								{/if}
   							{/if}     						
     					{else}
     						<script>showTopicVote('topic_vote_anonim',{$oTopic->getId()});</script>
     					{/if}
     					
     					<span class="padd_1">
     						<span style="padding-left: 4px; font-size: 11px; color: {if $oTopic->getRating()<0}#d00000{else}#008000{/if};" id="topic_rating_{$oTopic->getId()}" title="{if $oTopic->getCountVote()==0}{$aLang.topic_vote_no}{else}{$aLang.topic_vote_count}: {$oTopic->getCountVote()}{/if}">{$oTopic->getRating()}</span>
     					</span>
     				</div>
     				<div class="date">
     					<a href="#" title="{$aLang.topic_date}" onclick="return false;"><span>{date_format date=$oTopic->getDateAdd()}</span></a>
     				</div>    
     				{if $oTopic->getType()=='link'}
						<div class="link_url">						
							<a href="{$DIR_WEB_ROOT}/link/go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}"><span>{$oTopic->getLinkUrl(true)}</span></a>							
						</div>
					{/if}         			
     				<div class="user">
     					<a href="{$DIR_WEB_ROOT}/profile/{$oTopic->getUserLogin()}/" title="{$aLang.topic_user}"><span>{$oTopic->getUserLogin()}</span></a>
     				</div>     
          			<div class="comment_plashka">
          				{if $oTopic->getCountComment()>0}
          					<a href="{$DIR_WEB_ROOT}/blog/{if $oTopic->getBlogUrl()}{$oTopic->getBlogUrl()}/{/if}{$oTopic->getId()}.html#comments" title="{$aLang.topic_comment_read}"><span class="red">{$oTopic->getCountComment()}{if $oTopic->getCountCommentNew()} <font color="Green">+{$oTopic->getCountCommentNew()}</font>{/if}</span></a>
          				{else}
          					<a href="{$DIR_WEB_ROOT}/blog/{if $oTopic->getBlogUrl()}{$oTopic->getBlogUrl()}/{/if}{$oTopic->getId()}.html#comments" title="{$aLang.topic_comment_add}"><span class="red">{$aLang.topic_comment_add}</span></a>
          				{/if}
          			</div>
        		</div>
  			</div>
 		</div>
	</div>
	{/foreach}	
	
    {include file='paging.tpl' aPaging=`$aPaging`}			
	
{else}
{$aLang.blog_no_topic}
{/if}		
	
</DIV>