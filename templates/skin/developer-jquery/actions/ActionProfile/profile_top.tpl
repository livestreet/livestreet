<div class="profile">
	<a href="{$oUserProfile->getUserWebPath()}"><img src="{$oUserProfile->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
	
	<div id="vote_area_user_{$oUserProfile->getId()}" class="vote {if $oUserProfile->getRating()>=0}vote-count-positive{else}vote-count-negative{/if} {if $oVote} voted {if $oVote->getDirection()>0}voted-up{elseif $oVote->getDirection()<0}voted-down{/if}{/if}">
		<a href="#" class="vote-up" onclick="return ls.vote.vote({$oUserProfile->getId()},this,1,'user');"></a>
		<div id="vote_total_user_{$oUserProfile->getId()}" class="vote-count" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">{$oUserProfile->getRating()}</div>
		<a href="#" class="vote-down" onclick="return ls.vote.vote({$oUserProfile->getId()},this,-1,'user');"></a>
	</div>
	
	<p class="strength">
		{$aLang.user_skill}: <strong class="total" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</strong>
	</p>

	
	<h2 class="page-header user-login">{$oUserProfile->getLogin()}</h2>
	
	{if $oUserProfile->getProfileName()}
		<p class="user-name">{$oUserProfile->getProfileName()|escape:'html'}</p>
	{/if}

	{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}				
		<ul id="profile_actions">
			{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
			<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
		</ul>
	{/if}	
</div>