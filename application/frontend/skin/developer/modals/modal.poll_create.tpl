{**
 * Создание опроса
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-poll-create{/block}
{block name='modal_class'}modal-poll-create js-modal-default{/block}
{block name='modal_title'}{if $oPoll}Редактирование опроса{else}Создание опроса{/if}{/block}

{block name='modal_content'}
	<form action="" method="post" onsubmit="return false;" id="form-poll-create">

		{* Заголовок топика *}
		{include file='forms/fields/form.field.text.tpl'
			sFieldName				= 'poll[title]'
			sFieldValue				= {($oPoll) ? $oPoll->getTitle() : '' }
			sFieldLabel			= 'Название опроса'}

		{if $oPoll and $oPoll->getCountVote()}
			{$bDisableChangeType=true}
		{/if}

		Пользователь может выбрать:
		<label><input type="radio" name="poll[type]" value="one" {if !$oPoll or $oPoll->getCountAnswerMax()==1}checked="checked"{/if} {if $bDisableChangeType}disabled="disabled"{/if}> один вариант</label>
        <label><input type="radio" name="poll[type]" value="many" {if $oPoll and $oPoll->getCountAnswerMax()>1}checked="checked"{/if} {if $bDisableChangeType}disabled="disabled"{/if}>
			несколько вариантов
		</label>
		{include file='forms/fields/form.field.text.tpl'
			sFieldName				= 'poll[count_answer_max]'
			sFieldValue				= {($oPoll) ? $oPoll->getCountAnswerMax() : 2 }
			bFieldIsDisabled		= $bDisableChangeType }

        <div class="fieldset poll-add js-poll-add">
            <header class="fieldset-header">
                <h3 class="fieldset-title">{$aLang.topic_question_create_answers}</h3>
            </header>

            <ul class="fieldset-body poll-add-list js-poll-add-list">
            <script type="text/javascript">
				ls.poll.clearItemInit();
				{if $oPoll}
					{$aAnswers=$oPoll->getAnswers()}
					{foreach $aAnswers as $oAnswer}
						ls.poll.addItemInit({
							'answer_title': {json var=$oAnswer->getTitle()},
							'answer_id': {json var=$oAnswer->getId()},
							'disable_update': {json var=!$oPoll->isAllowUpdate()},
							'disable_remove': {json var=(!$oPoll->isAllowUpdate() || $oAnswer->getCountVote()) } });
					{/foreach}
				{else}
                    ls.poll.addItemInit({ });
                {/if}
            </script>
            </ul>

			{if !$oPoll or $oPoll->isAllowUpdate()}
                <footer class="fieldset-footer">
                    <button type="button" class="button button-primary js-poll-add-button" title="[Ctrl + Enter]">{$aLang.topic_question_create_answers_add}</button>
                </footer>
			{/if}
        </div>


		{if $oPoll}
			{include file='forms/fields/form.field.hidden.tpl' sFieldName='poll_id' sFieldValue=$oPoll->getId()}
		{else}
			{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[type]' sFieldValue=$sTargetType}
			{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[id]' sFieldValue=$sTargetId}
		{/if}

		{include file='forms/fields/form.field.hidden.tpl' sFieldName='target[tmp]' sFieldValue=$sTargetTmp}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="{if $oPoll}ls.poll.updatePoll('#form-poll-create',this);{else}ls.poll.createPoll('#form-poll-create',this);{/if}">{if $oPoll}{$aLang.common.save}{else}{$aLang.common.add}{/if}</button>
{/block}