{**
 * Базовый шаблон профиля пользователя
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_content_begin'}
	{**
	 * Шапка профиля
	 *}

	{$oVote = $oUserProfile->getVote()}

	<div class="profile">
		{hook run='profile_top_begin' oUserProfile=$oUserProfile}
		
		<a href="{$oUserProfile->getUserWebPath()}"><img src="{$oUserProfile->getProfileAvatarPath(48)}" alt="avatar" class="avatar" itemprop="photo" /></a>
		
		<div data-vote-type="user"
			 data-vote-id="{$oUserProfile->getId()}"
			 class="vote js-vote
			 	{if $oUserProfile->getRating() >= 0}
			 		vote-count-positive
			 	{else}
			 		vote-count-negative
			 	{/if} 

			 	{if $oVote}
			 		voted 

			 		{if $oVote->getDirection() > 0}
			 			voted-up
			 		{elseif $oVote->getDirection() < 0}
			 			voted-down
			 		{/if}
			 	{/if}">
			<div class="vote-label">{$aLang.user_rating}</div>
			<a href="#" class="vote-up js-vote-up"></a>
			<a href="#" class="vote-down js-vote-down"></a>
			<div class="vote-count count js-vote-rating" title="{$aLang.user_vote_count}: {$oUserProfile->getCountVote()}">{if $oUserProfile->getRating() > 0}+{/if}{$oUserProfile->getRating()}</div>
		</div>
		
		<div class="strength">
			<div class="vote-label">{$aLang.user_skill}</div>
			<div class="count" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
		</div>
		
		<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}" itemprop="nickname">{$oUserProfile->getLogin()}</h2>
		
		{if $oUserProfile->getProfileName()}
			<p class="user-name" itemprop="name">{$oUserProfile->getProfileName()|escape:'html'}</p>
		{/if}
		
		{hook run='profile_top_end' oUserProfile=$oUserProfile}
	</div>

	<h3 class="profile-page-header">{block name='layout_user_page_title'}{/block}</h3>
{/block}