{**
 * Базовый шаблон профиля пользователя
 *}

{extends './layout.base.tpl'}

{block 'layout_content_header' prepend}
	{**
	 * Шапка профиля
	 *}

	{$oVote = $oUserProfile->getVote()}

	<div class="profile">
		{hook run='profile_top_begin' oUserProfile=$oUserProfile}

		<a href="{$oUserProfile->getUserWebPath()}"><img src="{$oUserProfile->getProfileAvatarPath(48)}" alt="avatar" class="avatar" itemprop="photo" /></a>

		{* Голосование *}
		{include 'components/vote/vote.tpl'
				 sClasses   = 'js-vote-user'
				 sMods      = 'large'
				 oObject    = $oUserProfile
				 bIsLocked  = $oUserCurrent &&  $oUserCurrent->getId() == $oUserProfile->getId()
				 bShowLabel = true}

		<h2 class="page-header user-login word-wrap {if !$oUserProfile->getProfileName()}no-user-name{/if}" itemprop="nickname">{$oUserProfile->getDisplayName()}</h2>

		{if $oUserProfile->getProfileName()}
			<p class="user-name" itemprop="name">{$oUserProfile->getProfileName()|escape:'html'}</p>
		{/if}

		{hook run='profile_top_end' oUserProfile=$oUserProfile}
	</div>

	<h3 class="profile-page-header">{block 'layout_user_page_title'}{/block}</h3>
{/block}