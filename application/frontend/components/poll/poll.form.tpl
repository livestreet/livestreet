{**
 * Форма добавления опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<form action="" method="post" id="js-poll-form" data-action="{if $oPoll}update{else}add{/if}">
	{* Заголовок *}
	{component 'field' template='text'
			 name  = 'poll[title]'
			 value = {($oPoll) ? $oPoll->getTitle() : '' }
			 label = $aLang.poll.form.fields.title
			 inputAttributes= [ 'autofocus' => true ]}


	{component 'field' template='checkbox'
			name    = 'poll[is_guest_allow]'
			checked = {($oPoll && $oPoll->getIsGuestAllow()) ? true : false }
			label   = $aLang.poll.form.fields.is_guest_allow}


	{component 'field' template='checkbox'
			name    = 'poll[is_guest_check_ip]'
			checked = {($oPoll && $oPoll->getIsGuestCheckIp()) ? true : false }
			label   = $aLang.poll.form.fields.is_guest_check_ip}

	{* Кол-во вариантов которые может выбрать пользователь *}
	{if $oPoll && $oPoll->getCountVote()}
		{$bDisableChangeType = true}
	{/if}

	<p class="mb-10">{$aLang.poll.form.fields.type.label}:</p>

	{component 'field' template='radio'
			 name  = 'poll[type]'
			 value = 'one'
			 label = $aLang.poll.form.fields.type.label_one
			 checked = ! $oPoll or $oPoll->getCountAnswerMax() == 1
			 isDisabled = $bDisableChangeType}

	{component 'field' template='radio'
			 displayInline = true
			 name          = 'poll[type]'
			 value         = 'many'
			 label         = $aLang.poll.form.fields.type.label_many
			 checked       = $oPoll && $oPoll->getCountAnswerMax() > 1
			 isDisabled    = $bDisableChangeType}

	{component 'field' template='text'
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
				{component 'button'
						 type       = 'button'
						 text       = $aLang.common.add
						 attributes = [ 'title' => '[Ctrl + Enter]' ]
						 classes    = 'js-poll-form-answer-add'}
			</footer>
		{/if}
	</div>


	{* Скрытые поля *}
	{if $oPoll}
		{component 'field' template='hidden' name='poll_id' value=$oPoll->getId()}
	{else}
		{component 'field' template='hidden' name='target[type]' value=$sTargetType}
		{component 'field' template='hidden' name='target[id]' value=$sTargetId}
	{/if}

	{component 'field' template='hidden' name='target[tmp]' value=$sTargetTmp}
</form>

{* Шаблон ответа для добавления с помощью js *}
{include './poll.form.item.tpl' bPollItemIsTemplate=true}