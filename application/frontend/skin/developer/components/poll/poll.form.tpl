{**
 * Форма добавления опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<form action="" method="post" id="js-poll-form" data-action="{if $oPoll}update{else}add{/if}">
	{* Заголовок *}
	{include 'components/field/field.text.tpl'
			 name  = 'poll[title]'
			 value = {($oPoll) ? $oPoll->getTitle() : '' }
			 label = $aLang.poll.answer
			 inputAttributes="autofocus"}


	{* Кол-во вариантов которые может выбрать пользователь *}
	{if $oPoll && $oPoll->getCountVote()}
		{$bDisableChangeType = true}
	{/if}

	<p class="mb-10">{$aLang.poll.form.fields.type.label}:</p>

	{include 'components/field/field.radio.tpl'
			 name  = 'poll[type]'
			 value = 'one'
			 label = $aLang.poll.form.fields.type.label_one
			 checked = ! $oPoll or $oPoll->getCountAnswerMax() == 1
			 isDisabled = $bDisableChangeType}

	{include 'components/field/field.radio.tpl'
			 displayInline = true
			 name          = 'poll[type]'
			 value         = 'many'
			 label         = $aLang.poll.form.fields.type.label_many
			 checked       = $oPoll && $oPoll->getCountAnswerMax() > 1
			 isDisabled    = $bDisableChangeType}

	{include 'components/field/field.text.tpl'
			 displayInline = true
			 name          = 'poll[count_answer_max]'
			 value         = ($oPoll) ? $oPoll->getCountAnswerMax() : 2
			 classes       = 'width-50'
			 isDisabled    = $bDisableChangeType}


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
						{include './poll.form.item.tpl'
								 oPollItem          = $oAnswer
								 iPollItemIndex     = $oAnswer@index
								 bPollIsAllowUpdate = $oPoll->isAllowUpdate()
								 bPollIsAllowRemove = $oPoll->isAllowUpdate() && ! $oAnswer->getCountVote()}
					{/foreach}
				{else}
					{include './poll.form.item.tpl' showRemove=false}
					{include './poll.form.item.tpl' showRemove=false}
				{/if}
			</ul>
		</div>

		{if ! $oPoll or $oPoll->isAllowUpdate()}
			<footer class="fieldset-footer">
				{include 'components/button/button.tpl'
						 type       = 'button'
						 text       = $aLang.common.add
						 attributes = 'title="[Ctrl + Enter]"'
						 classes    = 'js-poll-form-answer-add'}
			</footer>
		{/if}
	</div>


	{* Скрытые поля *}
	{if $oPoll}
		{include 'components/field/field.hidden.tpl' name='poll_id' value=$oPoll->getId()}
	{else}
		{include 'components/field/field.hidden.tpl' name='target[type]' value=$sTargetType}
		{include 'components/field/field.hidden.tpl' name='target[id]' value=$sTargetId}
	{/if}

	{include 'components/field/field.hidden.tpl' name='target[tmp]' value=$sTargetTmp}
</form>

{* Шаблон ответа для добавления с помощью js *}
{include './poll.form.item.tpl' bPollItemIsTemplate=true}