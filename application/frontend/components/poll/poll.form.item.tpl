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
	{component 'field' template='hidden'
		name    = "answers[{$iPollItemIndex|default:0}][id]"
		value   = "{if $oPollItem}{$oPollItem->getId()}{/if}"
		classes = "js-poll-form-answer-item-id"}

	{* Текст *}
	{component 'field' template='text'
		name         = 'answers[]'
		value        = ($oPollItem) ? $oPollItem->getTitle() : ''
		isDisabled   = ! $bPollIsAllowUpdate|default:true
		inputClasses = 'width-full js-poll-form-answer-item-text'}

	{* Кнопка удаления *}
	{if $bPollIsAllowRemove|default:true}
		{component 'icon'
			icon='remove'
			classes='poll-form-answer-item-remove js-poll-form-answer-item-remove'
			attributes=[
				title => {lang 'blog.private'},
				style => "{if ! $smarty.local.showRemove|default:true}display: none{/if}"
			]}
	{/if}
</li>