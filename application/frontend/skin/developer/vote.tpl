{**
 * Голосование
 *
 * @param object  $oVoteObject     Объект сущности
 * @param string  $sVoteType       Название сущности (blog, topic и т.д.)
 * @param integer $iVoteId         ID сущности
 * @param string  $sVoteClasses    Дополнительные классы
 * @param boolean $bVoteShowRating Показывать рейтинг или нет
 * @param boolean $bVoteIsLocked   Блокировака голосования
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/vote.js
 *}

{$oVote = $oVoteObject->getVote()}
{$iVoteRating = $oVoteObject->getRating()}
{$bVoteShowRating = $bVoteShowRating|default:true}

<div data-vote-type="{$sVoteType}"
	 data-vote-id="{$oVoteObject->getId()}"

	 {* Параметры тултипа с инфой о голосовании *}
	 data-type="tooltip-toggle"
	 data-param-type="{$sVoteType}"
	 data-param-id="{$oVoteObject->getId()}"
	 data-tooltip-url="{router page='ajax'}vote/get/info/{$sVoteType}"

	 class="vote {if $sVoteType}vote-{$sVoteType}{/if} {$sVoteClasses} js-vote
		{if $bVoteShowRating}
			{if $iVoteRating > 0}
				vote-count-positive
			{elseif $iVoteRating < 0}
				vote-count-negative
			{/if}
		{/if}

		{if $oVote}
			vote-voted

			{if $oVote->getDirection() > 0}
				vote-voted-up
			{elseif $oVote->getDirection() < 0}
				vote-voted-down
			{elseif $oVote->getDirection() == 0}
				vote-voted-zero
			{/if}
		{/if}

		{if ! $oUserCurrent || $bVoteIsLocked}
			vote-locked
		{/if}

		{if $bVoteShowRating && $sVoteType == 'topic'}js-tooltip-vote-topic{/if}">
	{if $bVoteShowLabel|default:false}
		<div class="vote-label">{$aLang.vote.rating}</div>
	{/if}

	<div class="vote-item vote-up js-vote-up" title="{$aLang.vote.up}"><i></i></div>
	<div class="vote-item vote-down js-vote-down" title="{$aLang.vote.down}"><i></i></div>
	<div class="vote-item vote-rating js-vote-rating {if ! $bVoteShowRating && $sVoteType == 'topic'}js-vote-abstain{/if}" 
		 title="{if ! $bVoteShowRating}{$aLang.topic_vote_abstain}{else}{$aLang.vote.count}: {$oVoteObject->getCountVote()}{/if}">
		{if $bVoteShowRating}
			{$oVoteObject->getRating()}
		{else}
			?
		{/if}
	</div>
</div>