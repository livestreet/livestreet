{**
 * Блок добавления ответа
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<li class="poll-form-answer-item js-poll-form-answer-item" {if $bPollItemIsTemplate|default:false}data-is-template="true"{/if} {if $bPollItemIsTemplate|default:false}style="display: none"{/if}>
	<input type="hidden" name="answers[{$iPollItemIndex|default:0}][id]" value="{if $oPollItem}{$oPollItem->getId()}{/if}" class="js-poll-form-answer-item-id">

	<input type="text"
		   name="answers[{$iPollItemIndex|default:0}][title]"
		   class="width-full js-poll-form-answer-item-text"
		   value="{if $oPollItem}{$oPollItem->getTitle()}{/if}"
		   {if ! $bPollIsAllowUpdate|default:true}disabled{/if}>

	{if $bPollIsAllowRemove|default:true}
		<i class="icon-remove poll-form-answer-item-remove js-poll-form-answer-item-remove" title="{$aLang.common.remove}"></i>
	{/if}
</li>