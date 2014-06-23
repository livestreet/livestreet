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
				{include "components/field/field.{$type}.tpl"
						 sName    = 'answers[]'
						 sValue   = $answer->getId()
						 sLabel   = $answer->getTitle()
						 sClasses = 'js-poll-answer-$type'}
			</li>
		{/foreach}
	</ul>

	{include 'components/field/field.hidden.tpl' sName='id' sValue=$poll->getId()}

	{if $oUserCurrent}
		{* Проголосовать *}
		{include 'components/button/button.tpl' sText=$aLang.poll.vote sType='button' sMods='primary' sClasses='js-poll-vote'}

		{* Воздержаться *}
		{include 'components/button/button.tpl' sText=$aLang.poll.abstain sType='button' sClasses='js-poll-abstain'}
	{else}
		{* Предупрежение: голосовать могут только авторизованные пользователи *}
		{include 'components/alert/alert.tpl' sMods='info' mAlerts=$aLang.poll.only_auth}
	{/if}
</form>