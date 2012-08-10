<div class="profile">
	{hook run='profile_top_begin' oUserProfile=$oUserProfile}
	
	<div class="vote-profile">
		<div id="vote_area_user_{$oUserProfile->getId()}" class="vote-topic
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
			<div class="vote-item vote-down" onclick="return ls.vote.vote({$oUserProfile->getId()},this,-1,'user');"><span><i></i></span></div>
			<div class="vote-item vote-count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">
				<span id="vote_total_user_{$oUserProfile->getId()}">{if $oUserProfile->getRating() > 0}+{/if}{$oUserProfile->getRating()}</span>
			</div>
			<div class="vote-item vote-up" onclick="return ls.vote.vote({$oUserProfile->getId()},this,1,'user');"><span><i></i></span></div>
		</div>
		<div class="vote-label">{$aLang.user_rating}</div>
	</div>
	
	<div class="strength">
		<div class="count" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
		<div class="vote-label">{$aLang.user_skill}</div>
	</div>

	{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
		<a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}"><button type="submit"  class="button button-action button-action-send-message"><i class="icon-synio-send-message"></i><span>{$aLang.user_write_prvmsg}</span></button></a>
	{/if}

	<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}" itemprop="nickname">{$oUserProfile->getLogin()}</h2>
	
	{if $oUserProfile->getProfileName()}
		<p class="user-name" itemprop="name">{$oUserProfile->getProfileName()|escape:'html'}</p>
	{/if}
	
	{hook run='profile_top_end' oUserProfile=$oUserProfile}
</div>