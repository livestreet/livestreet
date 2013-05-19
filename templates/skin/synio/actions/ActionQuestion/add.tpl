{**
 * Создание топика-опроса
 *
 * @styles css/topic.css
 *}

{extends file='add.topic.base.tpl'}

{block name='add_topic_form_text_before'}
	<div class="poll-add">
		<label>{$aLang.topic_question_create_answers}:</label>
		<ul class="poll-add-list" id="question_list">
			{if count($_aRequest.answer) >= 2}
				{foreach from=$_aRequest.answer item=sAnswer key=i}
					<li>
						<input type="text" value="{$sAnswer}" name="answer[]" class="width-300" {if $bEditDisabled}disabled{/if} />
						{if !$bEditDisabled and $i>1} <a href="#" class="icon-synio-remove" onClick="return ls.poll.removeAnswer(this);"></a>{/if}
					</li>
				{/foreach}
			{else}
				<li><input type="text" value="" name="answer[]" class="width-300" {if $bEditDisabled}disabled{/if} /></li>
				<li><input type="text" value="" name="answer[]" class="width-300" {if $bEditDisabled}disabled{/if} /></li>
			{/if}
		</ul>
	
		{if ! $bEditDisabled}
			<button type="button" onclick="ls.poll.addAnswer(); return false;" class="button">{$aLang.topic_question_create_answers_add}</button>
		{/if}
	</div>
{/block}