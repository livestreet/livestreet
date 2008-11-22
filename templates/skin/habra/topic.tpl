
	<div class="text">
  		<!--medialand_ru_context_start-->    	
    	<h1 class="blog_headline">
  				<a href="{$oTopic->getBlogUrlFull()}" class="blog_headline_group">{$oTopic->getBlogTitle()|escape:'html'}</a>&nbsp;&#8594;&nbsp;
  				{if $oTopic->getType()=='link'}
  					<img src="{$DIR_STATIC_SKIN}/img/link_url_big.gif" border="0" title="{$aLang.topic_link}" width="16" height="16" alt="{$aLang.topic_link}">
  				{/if}
  				{if $oTopic->getPublish()==0}
  					<img src="{$DIR_STATIC_SKIN}/img/topic_unpublish.gif" border="0" title="{$aLang.topic_unpublish}">
  				{/if}
  				{$oTopic->getTitle()|escape:'html'}
  				{if $oUserCurrent and ( $oUserCurrent->getId()==$oTopic->getUserId() or $oUserCurrent->isAdministrator() or $oTopic->getUserIsBlogAdministrator() or $oTopic->getUserIsBlogModerator() or $oTopic->getBlogOwnerId()==$oUserCurrent->getId())}
  					<a href="{$DIR_WEB_ROOT}/{$oTopic->getType()}/edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}"><img src="{$DIR_STATIC_SKIN}/img/blog_edit.gif" border="0" title="{$aLang.topic_edit}"></a>
  				{/if}
  				{if $oUserCurrent and ($oUserCurrent->isAdministrator() or $oTopic->getUserIsBlogAdministrator() or $oTopic->getBlogOwnerId()==$oUserCurrent->getId())}
  					<a href="{$DIR_WEB_ROOT}/topic/delete/{$oTopic->getId()}/" title="{$aLang.topic_delete}" onclick="return confirm('{$aLang.topic_delete_confirm}');"><img src="{$DIR_STATIC_SKIN}/img/delete_16x16.gif" border="0" title="{$aLang.topic_delete}"></a>
  				{/if}
  				<a href="{$DIR_WEB_ROOT}/rss/comments/{$oTopic->getId()}/" title="RSS лента"><IMG  height=12 src="{$DIR_STATIC_SKIN}/img/rss_small.gif" width=12></a>
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
		    {$oTopic->getText()}   
		    <div style="clear: left;"></div>
    		<div class="posttags">
    			{$oTopic->getTagsLink()}
    		</div>
			<!--medialand_ru_context_end-->
	     	<div class="info_holder">
				<div class="ball first" id="voter1">					
     				
     					<span id="topic_vote_self_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_self}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_self}" />
     					</span>
     					<span id="topic_vote_anonim_{$oTopic->getId()}" style="display: none;" class="arrows_vote">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_up}" title="{$aLang.topic_vote_guest}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_guest}" />
     					</span>
     					<span id="topic_vote_is_vote_down_{$oTopic->getId()}" style="display: none;" class="{$aLang.topic_vote_up}">
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="нравится" title="{$aLang.topic_vote_already}" /><img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="{$aLang.topic_vote_down}" title="{$aLang.topic_vote_already}" />
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
				{if $oUserCurrent}
				<div class="ball">
					<span id="topic_favourite_add" {if $bInFavourite}style="display: none;"{/if} class="arrows_vote">
						<a href="#" title="{$aLang.topic_favourite_add}" onclick="ajaxTopicFavourite({$oTopic->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/favourite_gray.gif" width="16" height="16"></a>
					</span>
					<span id="topic_favourite_del" {if !$bInFavourite}style="display: none;"{/if} class="arrows_vote">
						<a href="#" title="{$aLang.topic_favourite_del}" onclick="ajaxTopicFavourite({$oTopic->getId()},0); return false;"><img src="{$DIR_STATIC_SKIN}/img/favourite.gif" width="16" height="16"></a>
					</span>&nbsp;		
				</div>  
				{/if}
				{if $oTopic->getType()=='link'}
				<div class="link_url">						
						<a href="{$DIR_WEB_ROOT}/link/go/{$oTopic->getId()}/" title="{$aLang.topic_link_count_jump}: {$oTopic->getLinkCountJump()}"><span>{$oTopic->getLinkUrl(true)}</span></a>							
				</div>
				{/if}
			<div class="user">
				<a href="{$DIR_WEB_ROOT}/profile/{$oTopic->getUserLogin()}/" title="{$aLang.topic_user}"><span>{$oTopic->getUserLogin()}</span></a>
			</div>
		</div>
	</div>