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

		{* Голосование *}
		{include 'vote.tpl' 
				 sVoteType      = 'user'
				 sVoteClasses   = 'vote-large'
				 oVoteObject    = $oUserProfile
				 bVoteIsLocked  = $oUserCurrent &&  $oUserCurrent->getId() == $oUserProfile->getId()
				 bVoteShowLabel = true}
		
		<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}" itemprop="nickname">{$oUserProfile->getDisplayName()}</h2>
		
		{if $oUserProfile->getProfileName()}
			<p class="user-name" itemprop="name">{$oUserProfile->getProfileName()|escape:'html'}</p>
		{/if}
		
		{hook run='profile_top_end' oUserProfile=$oUserProfile}
	</div>

	<h3 class="profile-page-header">{block name='layout_user_page_title'}{/block}</h3>
{/block}