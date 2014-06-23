{**
 * Блок добавления ответа
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<li class="poll-form-answer-item js-poll-form-answer-item"
	{if $bPollItemIsTemplate|default:false}data-is-template="true"{/if}
	{if $bPollItemIsTemplate|default:false}style="display: none"{/if}>

	{* ID *}
	{include 'components/field/field.hidden.tpl'
		sName    = "answers[{$iPollItemIndex|default:0}][id]"
		sValue   = "{if $oPollItem}{$oPollItem->getId()}{/if}"
		sClasses = "js-poll-form-answer-item-id"}

	{* Текст *}
	{include 'components/field/field.text.tpl'
		sName         = 'answers[]'
		sValue        = ($oPollItem) ? $oPollItem->getTitle() : ''
		bIsDisabled   = ! $bPollIsAllowUpdate|default:true
		sInputClasses = 'width-full js-poll-form-answer-item-text'}

	{* Кнопка удаления *}
	{if $bPollIsAllowRemove|default:true}
		<i class="icon-remove poll-form-answer-item-remove js-poll-form-answer-item-remove" title="{$aLang.common.remove}"></i>
	{/if}
</li>