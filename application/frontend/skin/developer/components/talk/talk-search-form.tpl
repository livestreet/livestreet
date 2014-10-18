{**
 * Поиск по личным сообщениям
 *}

<div class="accordion">
	<h3 class="accordion-header" onclick="jQuery('#block_talk_search_content').toggle(); return false;">
		<span class="link-dotted">{$aLang.talk.search.title}</span>
	</h3>

	<form action="{router page='talk'}" method="GET" name="talk_filter_form" id="block_talk_search_content" class="accordion-content" {if $_aRequest.submit_talk_filter}style="display:block;"{/if}>
		{* Отправитель *}
		{include 'components/field/field.text.tpl'
				 name    = 'sender'
				 label   = $aLang.talk.search.fields.sender.label
				 note    = $aLang.talk.search.fields.sender.note
				 inputClasses = 'width-full autocomplete-users'}

		{* Получатель *}
		{include 'components/field/field.text.tpl'
				name    = 'receiver'
				label   = $aLang.talk.search.fields.receiver.label
				note    = $aLang.talk.search.fields.receiver.note
				inputClasses = 'width-full autocomplete-users'}

		{* Искать в заголовке *}
		{include 'components/field/field.text.tpl'
				 name    = 'keyword'
				 label   = $aLang.talk.search.fields.keyword.label}

		{* Искать в тексте *}
		{include 'components/field/field.text.tpl'
				 name    = 'keyword_text'
				 label   = $aLang.talk.search.fields.keyword_text.label}

		{* Ограничения по дате *}
		{include 'components/field/field.text.tpl'
				name         = 'start'
				placeholder  = $aLang.talk.search.fields.start.placeholder
				label        = $aLang.talk.search.fields.start.label
				inputClasses = 'width-200 js-date-picker'}

		{include 'components/field/field.text.tpl'
				name         = 'end'
				placeholder  = $aLang.talk.search.fields.end.placeholder
				inputClasses = 'width-200 js-date-picker'}

		{* Искать только в избранном *}
		{include 'components/field/field.checkbox.tpl' name='favourite' label=$aLang.talk.search.fields.favourite.label}

		{* Кнопки *}
		{include 'components/button/button.tpl'
				name  = 'submit_talk_filter'
				value = '1'
				mods  = 'primary'
				text  = $aLang.search.find}

		{include 'components/button/button.tpl' type='reset' text=$aLang.common.form_reset}
	</form>
</div>