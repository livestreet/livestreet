{**
 * Форма добавления опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<form action="" method="post" id="form-poll-create" data-action="{if $oPoll}update{else}add{/if}">
	{* Заголовок топика *}
	{include file='forms/fields/form.field.text.tpl'
			 sFieldName  = 'poll[title]'
			 sFieldValue = {($oPoll) ? $oPoll->getTitle() : '' }
			 sFieldLabel = 'Вопрос'}


	{* Кол-во вариантов которые может выбрать пользователь *}
	{if $oPoll and $oPoll->getCountVote()}
		{$bDisableChangeType = true}
	{/if}

	Пользователь может выбрать:

	{include file='forms/fields/form.field.radio.tpl'
			 sFieldName  = 'poll[type]'
			 sFieldValue = 'one'
			 sFieldLabel = 'Один вариант'
			 bFieldChecked = ! $oPoll or $oPoll->getCountAnswerMax() == 1
			 bFieldIsDisabled = $bDisableChangeType}

	{include file='forms/fields/form.field.radio.tpl'
			 sFieldName  = 'poll[type]'
			 sFieldValue = 'many'
			 sFieldLabel = 'Несколько вариантов'
			 bFieldChecked = $oPoll and $oPoll->getCountAnswerMax() > 1
			 bFieldIsDisabled = $bDisableChangeType}

	{include file='forms/fields/form.field.text.tpl'
			 sFieldName       = 'poll[count_answer_max]'
			 sFieldValue      = ($oPoll) ? $oPoll->getCountAnswerMax() : 2
			 bFieldIsDisabled = $bDisableChangeType}


	{* Варианты ответов *}
	<div class="fieldset js-poll-add">
		<header class="fieldset-header">
			<h3 class="fieldset-title">{$aLang.poll.form.answers_title}</h3>
		</header>

		<ul class="fieldset-body poll-form-answer-list js-poll-form-answer-list">
			{if $oPoll}
				{$aAnswers = $oPoll->getAnswers()}

				{foreach $aAnswers as $oAnswer}
					{include 'polls/poll.form.answers.item.tpl'
							 oPollItem          = $oAnswer
							 iPollItemIndex     = $oAnswer@index
							 bPollIsAllowUpdate = $oPoll->isAllowUpdate()
							 bPollIsAllowRemove = $oPoll->isAllowUpdate() && ! $oAnswer->getCountVote()}
				{/foreach}
			{else}
				{include 'polls/poll.form.answers.item.tpl'}
			{/if}
		</ul>

		{if ! $oPoll or $oPoll->isAllowUpdate()}
			<footer class="fieldset-footer">
				<button type="button" class="button js-poll-form-answer-add" title="[Ctrl + Enter]">{$aLang.common.add}</button>
			</footer>
		{/if}
	</div>


	{* Скрытые поля *}
	{if $oPoll}
		{include file='forms/fields/form.field.hidden.tpl' sFieldName='poll_id' sFieldValue=$oPoll->getId()}
	{else}
		{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[type]' sFieldValue=$sTargetType}
		{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[id]' sFieldValue=$sTargetId}
	{/if}

	{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[tmp]' sFieldValue=$sTargetTmp}
</form>

{* Шаблон ответа для добавления с помощью js *}
{include 'polls/poll.form.answers.item.tpl' bPollItemIsTemplate=true}