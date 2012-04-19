<div class="profile">
	<a href="{$oUserProfile->getUserWebPath()}"><img src="{$oUserProfile->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
	
	<div id="vote_area_user_{$oUserProfile->getId()}" class="vote {if $oUserProfile->getRating()>=0}vote-count-positive{else}vote-count-negative{/if} {if $oVote} voted {if $oVote->getDirection()>0}voted-up{elseif $oVote->getDirection()<0}voted-down{/if}{/if}">
		<div class="vote-label">Рейтинг</div>
		<a href="#" class="vote-up" onclick="return ls.vote.vote({$oUserProfile->getId()},this,1,'user');"></a>
		<a href="#" class="vote-down" onclick="return ls.vote.vote({$oUserProfile->getId()},this,-1,'user');"></a>
		<div id="vote_total_user_{$oUserProfile->getId()}" class="vote-count count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">{if $oUserProfile->getRating() > 0}+{/if}{$oUserProfile->getRating()}</div>
	</div>
	
	<div class="strength">
		<div class="vote-label">{$aLang.user_skill}</div>
		<div class="count" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
	</div>
	
	<h2 class="page-header user-login {if !$oUserProfile->getProfileName()}no-user-name{/if}">{$oUserProfile->getLogin()}</h2>
	
	{if $oUserProfile->getProfileName()}
		<p class="user-name">{$oUserProfile->getProfileName()|escape:'html'}</p>
	{/if}
</div>