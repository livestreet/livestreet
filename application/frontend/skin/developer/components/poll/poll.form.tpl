{**
 * Форма добавления опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<form action="" method="post" id="js-poll-form" data-action="{if $oPoll}update{else}add{/if}">
	{* Заголовок *}
	{include 'components/field/field.text.tpl'
			 sName  = 'poll[title]'
			 sValue = {($oPoll) ? $oPoll->getTitle() : '' }
			 sLabel = $aLang.poll.answer
			 sInputAttributes="autofocus"}


	{* Кол-во вариантов которые может выбрать пользователь *}
	{if $oPoll and $oPoll->getCountVote()}
		{$bDisableChangeType = true}
	{/if}

	<p class="mb-10">{$aLang.poll.form.fields.type.label}:</p>

	{include 'components/field/field.radio.tpl'
			 sName  = 'poll[type]'
			 sValue = 'one'
			 sLabel = $aLang.poll.form.fields.type.label_one
			 bChecked = ! $oPoll or $oPoll->getCountAnswerMax() == 1
			 bIsDisabled = $bDisableChangeType}

	{include 'components/field/field.radio.tpl'
			 bDisplayInline = true
			 sName          = 'poll[type]'
			 sValue         = 'many'
			 sLabel         = $aLang.poll.form.fields.type.label_many
			 bChecked       = $oPoll and $oPoll->getCountAnswerMax() > 1
			 bIsDisabled    = $bDisableChangeType}

	{include 'components/field/field.text.tpl'
			 bDisplayInline = true
			 sName          = 'poll[count_answer_max]'
			 sValue         = ($oPoll) ? $oPoll->getCountAnswerMax() : 2
			 sClasses       = 'width-50'
			 bIsDisabled    = $bDisableChangeType}


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
					{include './poll.form.item.tpl'}
				{/if}
			</ul>
		</div>

		{if ! $oPoll or $oPoll->isAllowUpdate()}
			<footer class="fieldset-footer">
				{include 'components/button/button.tpl'
						 sType       = 'button'
						 sText       = $aLang.common.add
						 sAttributes = 'title="[Ctrl + Enter]"'
						 sClasses    = 'js-poll-form-answer-add'}
			</footer>
		{/if}
	</div>


	{* Скрытые поля *}
	{if $oPoll}
		{include 'components/field/field.hidden.tpl' sName='poll_id' sValue=$oPoll->getId()}
	{else}
		{include 'components/field/field.hidden.tpl' sName='target[type]' sValue=$sTargetType}
		{include 'components/field/field.hidden.tpl' sName='target[id]' sValue=$sTargetId}
	{/if}

	{include 'components/field/field.hidden.tpl' sName='target[tmp]' sValue=$sTargetTmp}
</form>

{* Шаблон ответа для добавления с помощью js *}
{include './poll.form.item.tpl' bPollItemIsTemplate=true}