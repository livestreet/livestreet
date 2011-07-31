{if count($aStreamEvents)}
		{foreach from=$aStreamEvents item=oStreamEvent}		
			{assign var=oTarget value=$oStreamEvent->getTarget()}
			<li class="stream-item-type-{$oStreamEvent->getEventType()}">
				<a href="{$oStreamEvent->getUser()->getUserWebPath()}"><img src="{$oStreamEvent->getUser()->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
				<span class="date">{date_format date=$oStreamEvent->getDateAdded()}</span> 

				<a href="{$oStreamEvent->getUser()->getUserWebPath()}"><strong>{$oStreamEvent->getUser()->getLogin()}</strong></a>
			
				{if $oStreamEvent->getEventType() == 'add_topic'}
					{$aLang.stream_list_event_add_topic} <a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'add_comment'}
					{$aLang.stream_list_event_add_comment} <a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'add_blog'}
					{$aLang.stream_list_event_add_blog} <a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'vote_blog'}
					{$aLang.stream_list_event_vote_blog} <a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'vote_topic'}
					{$aLang.stream_list_event_vote_topic} <a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'vote_comment'}
					{$aLang.stream_list_event_vote_comment} <a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'vote_user'}
					{$aLang.stream_list_event_vote_user} <a href="{$oTarget->getUserWebPath()}">{$oTarget->getLogin()}</a>
				{elseif $oStreamEvent->getEventType() == 'join_blog'}
					{$aLang.stream_list_event_join_blog} <a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
				{elseif $oStreamEvent->getEventType() == 'add_friend'}
					{$aLang.stream_list_event_add_friend} <a href="{$oTarget->getUserWebPath()}">{$oTarget->getLogin()}</a>
				{/if}
			</li>
		{/foreach}
{/if}