{**
 * Поиск по личным сообщениям
 *}
<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_talk_search_content').toggle(); return false;"><span class="link-dotted">{$aLang.messages.search.title}</span></h3>

	<form action="{router page='talk'}" method="GET" name="talk_filter_form" id="block_talk_search_content" class="accordion-content" {if $_aRequest.submit_talk_filter}style="display:block;"{/if}>
		{* Отправитель *}
		{include file='forms/fields/form.field.text.tpl'
				 sFieldName    = 'sender'
				 sFieldLabel   = $aLang.messages.search.fields.sender.label
				 sFieldNote    = $aLang.messages.search.fields.sender.note
				 sFieldClasses = 'width-full autocomplete-users-sep'}

		{* Искать в заголовке *}
		{include file='forms/fields/form.field.text.tpl'
				 sFieldName    = 'keyword'
				 sFieldLabel   = $aLang.messages.search.fields.keyword.label}

		{* Искать в тексте *}
		{include file='forms/fields/form.field.text.tpl'
				 sFieldName    = 'keyword_text'
				 sFieldLabel   = $aLang.messages.search.fields.keyword_text.label}

		{* Ограничения по дате *}
		{include file='forms/fields/form.field.text.tpl'
				 sFieldName        = 'start'
				 sFieldPlaceholder = $aLang.messages.search.fields.start.placeholder
				 sFieldLabel       = $aLang.messages.search.fields.start.label
				 sFieldClasses     = 'width-200 date-picker'}

		{include file='forms/fields/form.field.text.tpl'
				 sFieldName        = 'end'
				 sFieldPlaceholder = $aLang.messages.search.fields.end.placeholder
				 sFieldClasses     = 'width-200 date-picker'}

		{* Искать только в избранном *}
		{include file='forms/fields/form.field.checkbox.tpl' sFieldName='favourite' sFieldLabel=$aLang.messages.search.fields.favourite.label}

		{* Кнопки *}
		{include file='forms/fields/form.field.button.tpl'
		 		 sFieldName    = 'submit_talk_filter'
		 		 sFieldStyle   = 'primary'
		 		 sFieldText    = $aLang.search.find}

		{include file='forms/fields/form.field.button.tpl' sFieldType='reset' sFieldText=$aLang.common.form_reset}
	</form>
</div>