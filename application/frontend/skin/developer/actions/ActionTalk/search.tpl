{**
 * Поиск по личным сообщениям
 *}
<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_talk_search_content').toggle(); return false;"><span class="link-dotted">{$aLang.messages.search.title}</span></h3>

	<form action="{router page='talk'}" method="GET" name="talk_filter_form" id="block_talk_search_content" class="accordion-content" {if $_aRequest.submit_talk_filter}style="display:block;"{/if}>
		{* Отправитель *}
		{include file='components/field/field.text.tpl'
				 sName    = 'sender'
				 sLabel   = $aLang.messages.search.fields.sender.label
				 sNote    = $aLang.messages.search.fields.sender.note
				 sClasses = 'width-full autocomplete-users-sep'}

		{* Искать в заголовке *}
		{include file='components/field/field.text.tpl'
				 sName    = 'keyword'
				 sLabel   = $aLang.messages.search.fields.keyword.label}

		{* Искать в тексте *}
		{include file='components/field/field.text.tpl'
				 sName    = 'keyword_text'
				 sLabel   = $aLang.messages.search.fields.keyword_text.label}

		{* Ограничения по дате *}
		{include file='components/field/field.text.tpl'
				 sName        = 'start'
				 sPlaceholder = $aLang.messages.search.fields.start.placeholder
				 sLabel       = $aLang.messages.search.fields.start.label
				 sClasses     = 'width-200 date-picker'}

		{include file='components/field/field.text.tpl'
				 sName        = 'end'
				 sPlaceholder = $aLang.messages.search.fields.end.placeholder
				 sClasses     = 'width-200 date-picker'}

		{* Искать только в избранном *}
		{include file='components/field/field.checkbox.tpl' sName='favourite' sLabel=$aLang.messages.search.fields.favourite.label}

		{* Кнопки *}
		{include file='components/button/button.tpl'
		 		 sName    = 'submit_talk_filter'
		 		 sStyle   = 'primary'
		 		 sText    = $aLang.search.find}

		{include file='components/button/button.tpl' sType='reset' sText=$aLang.common.form_reset}
	</form>
</div>