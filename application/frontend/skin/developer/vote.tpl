{**
 * Голосование
 *
 * @param object  $oVoteObject Объект сущности
 * @param string  $sVoteType   Название сущности (blog, topic и т.д.)
 * @param integer $iVoteId     ID сущности
 * @param integer $sVoteStyle  Стиль
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/vote.js
 *}

{$oVote = $oVoteObject->getVote()}
{$iVoteRating = $oVoteObject->getRating()}

<div data-vote-type="{$sVoteType}"
	 data-vote-id="{$oVoteObject->getId()}"
	 class="vote {if $sVoteType}vote-{$sVoteType}{/if} {if $sVoteStyle}vote-{$sVoteStyle}{/if} js-vote
		{if $iVoteRating > 0}
			vote-count-positive
		{elseif $iVoteRating < 0}
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
	{if $bVoteShowLabel|default:false}
		<div class="vote-label">{$aLang.vote.rating}</div>
	{/if}

	<div class="vote-up js-vote-up" title="{$aLang.vote.up}"><i></i></div>
	<div class="vote-down js-vote-down" title="{$aLang.vote.down}"><i></i></div>
	<div class="vote-count count js-vote-rating" title="{$aLang.vote.count}: {$oVoteObject->getCountVote()}">{$oVoteObject->getRating()}</div>
</div>