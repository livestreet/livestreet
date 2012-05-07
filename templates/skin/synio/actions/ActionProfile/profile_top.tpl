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
																	{/if}
																	
																	{if $oUserCurrent && $oUserProfile->getId() == $oUserCurrent->getId()}
																		vote-nobuttons
																	{/if}">
			<div href="#" class="vote-item vote-down" onclick="return ls.vote.vote({$oUserProfile->getId()},this,-1,'user');"><span><i></i></span></div>
			<div id="vote_total_user_{$oUserProfile->getId()}" class="vote-item vote-count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">
				<span>{if $oUserProfile->getRating() > 0}+{/if}{$oUserProfile->getRating()}</span>
			</div>
			<div href="#" class="vote-item vote-up" onclick="return ls.vote.vote({$oUserProfile->getId()},this,1,'user');"><span><i></i></span></div>
		</div>
		<div class="vote-label">{$aLang.user_rating}</div>
	</div>
	
	<div class="strength">
		<div class="count" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
		<div class="vote-label">{$aLang.user_skill}</div>
	</div>
	
	<button class="button button-action button-action-add-friend"><i class="icon-synio-add-friend"></i><span>В друзья</span></button>
	<button class="button button-action button-action-send-message"><i class="icon-synio-send-message"></i></button>
	
	<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}">{$oUserProfile->getLogin()}</h2>
	
	{if $oUserProfile->getProfileName()}
		<p class="user-name">{$oUserProfile->getProfileName()|escape:'html'}</p>
	{/if}
	
	{hook run='profile_top_end' oUserProfile=$oUserProfile}
</div>