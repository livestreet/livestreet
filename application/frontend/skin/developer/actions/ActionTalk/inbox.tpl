{**
 * Список сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_options'}
	{$bNoSystemMessages = false}
{/block}

{block name='layout_content'}
	{if $aTalks}
		{**
		 * Поиск по личным сообщениям
		 *}
		<div class="accordion">
			<h3 class="accordion-header" onclick="jQuery('#block_talk_search_content').toggle(); return false;"><span class="link-dotted">{$aLang.talk_filter_title}</span></h3>
				
			<form action="{router page='talk'}" method="GET" name="talk_filter_form" id="block_talk_search_content" class="accordion-content" {if $_aRequest.submit_talk_filter}style="display:block;"{/if}>
				{* Отправитель *}
				{include file='forms/fields/form.field.text.tpl'
						 sFieldName    = 'sender'
						 sFieldClasses = 'width-full autocomplete-users-sep'
						 sFieldLabel   = $aLang.talk_filter_label_sender
						 sFieldNote    = $aLang.talk_filter_notice_sender}


				{* Искать в заголовке *}
				{include file='forms/fields/form.field.text.tpl'
						 sFieldName    = 'keyword'
						 sFieldLabel   = $aLang.talk_filter_label_keyword
						 sFieldNote    = $aLang.talk_filter_notice_keyword}


				{* Искать в тексте *}
				{include file='forms/fields/form.field.text.tpl'
						 sFieldName    = 'keyword_text'
						 sFieldLabel   = $aLang.talk_filter_label_keyword_text
						 sFieldNote    = $aLang.talk_filter_notice_keyword}


				{* Ограничения по дате *}
				{* TODO: i18n *}
				{include file='forms/fields/form.field.text.tpl'
						 sFieldName        = 'start'
						 sFieldClasses     = 'width-200 date-picker'
						 sFieldPlaceholder = 'From'
						 sFieldLabel       = $aLang.talk_filter_label_date}

				{include file='forms/fields/form.field.text.tpl'
						 sFieldName        = 'end'
						 sFieldPlaceholder = 'To'
						 sFieldClasses     = 'width-200 date-picker'}


				{* Искать только в избранном *}
				{include file='forms/fields/form.field.checkbox.tpl'
						 sFieldName  = 'favourite'
						 sFieldLabel = $aLang.talk_filter_label_favourite}


				{* Кнопки *}
				{include file='forms/fields/form.field.button.tpl'
				 		 sFieldName    = 'submit_talk_filter'
				 		 sFieldStyle   = 'primary'
				 		 sFieldText    = $aLang.talk_filter_submit}
				{include file='forms/fields/form.field.button.tpl' sFieldType='reset' sFieldText=$aLang.talk_filter_submit_clear}
			</form>
		</div>
		

		{**
		 * Список сообщений
		 *}
		<form action="{router page='talk'}" method="post" id="form_talks_list">
			<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
			<input type="hidden" name="submit_talk_read" id="form_talks_list_submit_read" value="" />
			<input type="hidden" name="submit_talk_del" id="form_talks_list_submit_del" value="" />

			<button type="submit" onclick="ls.talk.makeReadTalks()" class="button">{$aLang.talk_inbox_make_read}</button>
			<button type="submit" onclick="if (confirm('{$aLang.talk_inbox_delete_confirm}')){ ls.talk.removeTalks() };" class="button">{$aLang.talk_inbox_delete}</button>
			<br /><br />
			
			{include file='actions/ActionTalk/message_list.tpl' bMessageListCheckboxes=true}
		</form>
	{else}
		{include file='alert.tpl' mAlerts=$aLang.talk_inbox_empty sAlertStyle='empty'}
	{/if}

				
	{include file='pagination.tpl' aPaging=$aPaging}
{/block}