{**
 * Форма добавления опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<form action="" method="post" id="js-poll-form" data-action="{if $oPoll}update{else}add{/if}">
	{* Заголовок топика *}
	{include file='forms/fields/form.field.text.tpl'
			 sFieldName  = 'poll[title]'
			 sFieldValue = {($oPoll) ? $oPoll->getTitle() : '' }
			 sFieldLabel = $aLang.poll.answer}


	{* Кол-во вариантов которые может выбрать пользователь *}
	{if $oPoll and $oPoll->getCountVote()}
		{$bDisableChangeType = true}
	{/if}

	<p class="mb-10">{$aLang.poll.form.fields.type.label}:</p>

	{include file='forms/fields/form.field.radio.tpl'
			 sFieldName  = 'poll[type]'
			 sFieldValue = 'one'
			 sFieldLabel = $aLang.poll.form.fields.type.label_one
			 bFieldChecked = ! $oPoll or $oPoll->getCountAnswerMax() == 1
			 bFieldIsDisabled = $bDisableChangeType}

	{include file='forms/fields/form.field.radio.tpl'
			 bFieldDisplayInline = true
			 sFieldName          = 'poll[type]'
			 sFieldValue         = 'many'
			 sFieldLabel         = $aLang.poll.form.fields.type.label_many
			 bFieldChecked       = $oPoll and $oPoll->getCountAnswerMax() > 1
			 bFieldIsDisabled    = $bDisableChangeType}

	{include file='forms/fields/form.field.text.tpl'
			 bFieldDisplayInline = true
			 sFieldName          = 'poll[count_answer_max]'
			 sFieldValue         = ($oPoll) ? $oPoll->getCountAnswerMax() : 2
			 sFieldClasses       = 'width-50'
			 bFieldIsDisabled    = $bDisableChangeType}


	{* Варианты ответов *}
	<div class="fieldset m-0">
		<header class="fieldset-header">
			<h3 class="fieldset-title">{$aLang.poll.form.answers_title}</h3>
		</header>

		<div class="fieldset-body">
			<ul class="poll-form-answer-list js-poll-form-answer-list">
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
		</div>

		{if ! $oPoll or $oPoll->isAllowUpdate()}
			<footer class="fieldset-footer">
				{include file='forms/fields/form.field.button.tpl'
						 sFieldType       = 'button'
						 sFieldText       = $aLang.common.add
						 sFieldAttributes = 'title="[Ctrl + Enter]"'
						 sFieldClasses    = 'js-poll-form-answer-add'}
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