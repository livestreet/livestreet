{**
 * Форма голосования
 *
 * @param ModulePoll_EntityPoll $poll Опрос
 *}

{* Тип голосования *}
{* Если можно выбрать больше одного варианта, то показываем чекбоксы, иначе радио-кнопки *}
{$type = ( $poll->getCountAnswerMax() > 1 ) ? 'checkbox' : 'radio'}

{* Форма *}
<form method="post" class="js-poll-vote-form">
	{* Список ответов *}
	<ul class="poll-answer-list">
		{foreach $poll->getAnswers() as $answer}
			<li class="poll-answer-list-item js-poll-answer-list-item" data-answer-id="{$answer->getId()}">
				{component 'field' template=$type
					name    = 'answers[]'
					value   = $answer->getId()
					label   = $answer->getTitle()
					classes = 'js-poll-answer-$type'}
			</li>
		{/foreach}
	</ul>

	{component 'field' template='hidden' name='id' value=$poll->getId()}

	{if $oUserCurrent or $poll->getIsGuestAllow()}
		{* Проголосовать *}
		{component 'button' text=$aLang.poll.vote type='button' mods='primary' classes='js-poll-vote'}

		{* Воздержаться *}
		{component 'button' text=$aLang.poll.abstain type='button' classes='js-poll-abstain'}
	{else}
		{* Предупрежение: голосовать могут только авторизованные пользователи *}
		{component 'alert' mods='info' text=$aLang.poll.only_auth}
	{/if}
</form>