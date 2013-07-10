{**
 * Базовый шаблон профиля пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$sNav = 'users'}
{/block}

{block name='layout_content_begin'}
	{**
	 * Шапка профиля
	 *}

	{$oVote = $oUserProfile->getVote()}
	
	<div class="profile">
		{hook run='profile_top_begin' oUserProfile=$oUserProfile}
		
		<div class="vote-profile">
			<div data-vote-type="user"
				 data-vote-id="{$oUserProfile->getId()}"
				 class="vote-topic js-vote
					{if $oUserProfile->getRating() > 0}
						vote-count-positive
					{elseif $oUserProfile->getRating() < 0}
						vote-count-negative
					{elseif $oUserProfile->getRating() == 0}
						vote-count-zero
					{/if}
					
					{if $oVote} 
						voted 
						
						{if $oVote->getDirection() > 0}
							voted-up
						{elseif $oVote->getDirection() < 0}
							voted-down
						{/if}
					{else}
						not-voted
					{/if}
					
					{if ($oUserCurrent && $oUserProfile->getId() == $oUserCurrent->getId()) || !$oUserCurrent}
						vote-nobuttons
					{/if}">
				<div class="vote-item vote-down js-vote-down"><span><i></i></span></div>
				<div class="vote-item vote-count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">
					<span class="js-vote-rating">{if $oUserProfile->getRating() > 0}+{/if}{$oUserProfile->getRating()}</span>
				</div>
				<div class="vote-item vote-up js-vote-up"><span><i></i></span></div>
			</div>
			<div class="vote-label">{$aLang.user_rating}</div>
		</div>
		
		<div class="strength">
			<div class="count" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
			<div class="vote-label">{$aLang.user_skill}</div>
		</div>

		{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
			<a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}" class="button button-action button-action-send-message button-icon js-tooltip" title="{$aLang.user_write_prvmsg}">
				<i class="icon-synio-send-message"></i>
			</a>
		{/if}

		<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}" itemprop="nickname">{$oUserProfile->getLogin()}</h2>
		
		{if $oUserProfile->getProfileName()}
			<p class="user-name" itemprop="name">{$oUserProfile->getProfileName()|escape:'html'}</p>
		{/if}
		
		{hook run='profile_top_end' oUserProfile=$oUserProfile}
	</div>
{/block}