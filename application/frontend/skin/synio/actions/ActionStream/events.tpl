{**
 * События (добавлен комментарий, добавлен топик и т.д.)
 *}

{if count($aStreamEvents)}
	{foreach $aStreamEvents as $oStreamEvent}		
		{$oTarget = $oStreamEvent->getTarget()}
		{$oUser = $oStreamEvent->getUser()}
		{$bUserIsMale = $oUser->getProfileSex() != 'woman'}

		
		{* Дата группы событий *}
		{if {date_format date=$oStreamEvent->getDateAdded() format="j F Y"} != $sDateLast}
			{$sDateLast = {date_format date=$oStreamEvent->getDateAdded() format="j F Y"}}
			
			<li class="activity-date">
				{if {date_format date=$smarty.now format="j F Y"} == $sDateLast}
					{$aLang.today}
				{else}
					{date_format date=$oStreamEvent->getDateAdded() format="j F Y"}
				{/if}
			</li>
		{/if}


		<li class="activity-event activity-event-type-{$oStreamEvent->getEventType()}">
			{* Аватар *}
			<a href="{$oUser->getUserWebPath()}">
				<img src="{$oUser->getProfileAvatarPath(48)}" alt="{$oUser->getLogin()}" class="activity-event-avatar" />
			</a>
			
			<p class="activity-event-info">
				{* Логин *}
				<a href="{$oUser->getUserWebPath()}"><strong>{$oUser->getLogin()}</strong></a> ·

				{* Дата *}
				<time datetime="{date_format date=$oStreamEvent->getDateAdded() format='c'}"
					  class="activity-event-date" 
					  title="{date_format date=$oStreamEvent->getDateAdded()}">
					{date_format date=$oStreamEvent->getDateAdded() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
				</time>
			</p>

			{* 
			 * Текст события 
			 *}
			{if $oStreamEvent->getEventType() == 'add_topic'}
				{* Добавлен топик *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_add_topic}
				{else}
					{$aLang.stream_list_event_add_topic_female}
				{/if}

				<a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'add_comment'}
				{* Добавлен комментарий *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_add_comment}
				{else}
					{$aLang.stream_list_event_add_comment_female}
				{/if}

				<a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>

				{$sTextEvent = $oTarget->getText()}

				{if trim($sTextEvent)}
					<div class="activity-event-text">
						<div class="text">
							{$sTextEvent}
						</div>
					</div>
				{/if}
			{elseif $oStreamEvent->getEventType() == 'add_blog'}
				{* Создан блог *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_add_blog}
				{else}
					{$aLang.stream_list_event_add_blog_female}
				{/if}

				<a href="{$oTarget->getUrlFull()}">{$oTarget->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'vote_blog'}
				{* Проголосовали за блог *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_vote_blog}
				{else}
					{$aLang.stream_list_event_vote_blog_female}
				{/if}

				<a href="{$oTarget->getUrlFull()}">{$oTarget->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'vote_topic'}
				{* Проголосовали за топик *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_vote_topic}
				{else}
					{$aLang.stream_list_event_vote_topic_female}
				{/if}

				<a href="{$oTarget->getUrl()}">{$oTarget->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'vote_comment'}
				{* Проголосовали за комментарий *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_vote_comment}
				{else}
					{$aLang.stream_list_event_vote_comment_female}
				{/if}

				<a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'vote_user'}
				{* Проголосовали за пользователя *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_vote_user}
				{else}
					{$aLang.stream_list_event_vote_user_female}
				{/if}

				<span class="user-avatar user-avatar-n">
					<a href="{$oTarget->getUserWebPath()}"><img src="{$oTarget->getProfileAvatarPath(24)}" alt="avatar" /></a>
					<a href="{$oTarget->getUserWebPath()}">{$oTarget->getLogin()}</a>
				</span>
			{elseif $oStreamEvent->getEventType() == 'join_blog'}
				{* Вступили в блог *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_join_blog}
				{else}
					{$aLang.stream_list_event_join_blog_female}
				{/if}

				<a href="{$oTarget->getUrlFull()}">{$oTarget->getTitle()|escape:'html'}</a>
			{elseif $oStreamEvent->getEventType() == 'add_friend'}
				{* Добавили в друзья *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_add_friend}
				{else}
					{$aLang.stream_list_event_add_friend_female}
				{/if}

				<span class="user-avatar user-avatar-n">
					<a href="{$oTarget->getUserWebPath()}"><img src="{$oTarget->getProfileAvatarPath(24)}" alt="avatar" /></a>
					<a href="{$oTarget->getUserWebPath()}">{$oTarget->getLogin()}</a>
				</span>
			{elseif $oStreamEvent->getEventType() == 'add_wall'}
				{* Написали на стене *}

				{if $bUserIsMale}
					{$aLang.stream_list_event_add_wall}
				{else}
					{$aLang.stream_list_event_add_wall_female}
				{/if}

				<span class="user-avatar user-avatar-n">
					<a href="{$oTarget->getWallUser()->getUserWebPath()}"><img src="{$oTarget->getWallUser()->getProfileAvatarPath(24)}" alt="avatar" /></a>
					<a href="{$oTarget->getUrlWall()}">{$oTarget->getWallUser()->getLogin()}</a>
				</span>

				{$sTextEvent = $oTarget->getText()}

				{if trim($sTextEvent)}
					<div class="activity-event-text">
						<div class="text">
							{$sTextEvent}
						</div>
					</div>
				{/if}
			{else}
				{hook run="stream_list_event_`$oStreamEvent->getEventType()`" oStreamEvent=$oStreamEvent}
			{/if}
		</li>
	{/foreach}


	<script>
		ls.stream.sDateLast = {json var=$sDateLast};
	</script>
{/if}