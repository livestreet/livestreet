{**
 * Создание топика-опроса
 *
 * @styles css/topic.css
 * @scripts <framework>/js/livestreet/poll.js
 *}

{extends file='forms/form.add.topic.base.tpl'}


{block name='add_topic_type'}question{/block}

{block name='add_topic_form_text_before'}
	<div class="poll-add js-poll-add">
		<h3 class="h6">{$aLang.topic_question_create_answers}</h3>

		<ul class="poll-add-list js-poll-add-list">
			{if count($_aRequest.answer) >= 2}
				{foreach $_aRequest.answer as $sAnswer}
					<li class="poll-add-item js-poll-add-item">
						<input type="text" value="{$sAnswer}" name="answer[]" class="poll-add-item-input js-poll-add-item-input" {if $bEditDisabled}disabled{/if} />

						{if ! $bEditDisabled and $sAnswer@key > 1}
							<i class="icon-remove poll-add-item-remove js-poll-add-item-remove" title="{$aLang.topic_question_create_answers_delete}"></i>
						{/if}
					</li>
				{/foreach}
			{else}
				<li class="poll-add-item js-poll-add-item"><input type="text" name="answer[]" class="poll-add-item-input js-poll-add-item-input" {if $bEditDisabled}disabled{/if} /></li>
				<li class="poll-add-item js-poll-add-item"><input type="text" name="answer[]" class="poll-add-item-input js-poll-add-item-input" {if $bEditDisabled}disabled{/if} /></li>
			{/if}
		</ul>

		{if ! $bEditDisabled}
			<button type="button" class="button button-primary js-poll-add-button" title="[Ctrl + Enter]">{$aLang.topic_question_create_answers_add}</button>
		{/if}
	</div>
{/block}