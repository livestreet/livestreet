{**
 * Создание личного сообщения
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include 'forms/editor.init.tpl' sEditorType='comment' sMediaTargetType='talk'}

	{hook run='talk_add_begin'}

	<form action="" method="POST" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_add_talk_begin'}

		{include 'forms/fields/form.field.hidden.security_key.tpl'}

		{* Получатели *}
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'talk_users'
				 sFieldRules   = 'required="true" rangetags="[1,99]"'
				 sFieldLabel   = $aLang.talk_create_users
				 sFieldClasses = 'width-full autocomplete-users-sep'
				 sFieldNote    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\" data-param-target=\".js-input-talk_users\">Выбрать из списка друзей</a>"}

		{* Заголовок *}
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'talk_title'
				 sFieldRules   = 'required="true" rangelength="[2,200]"'
				 sFieldLabel   = $aLang.talk_create_title}

		{* Текст сообщения *}
		{include 'forms/fields/form.field.textarea.tpl'
				 sFieldName    = 'talk_text'
				 sFieldRules   = 'required="true" rangelength="[2,3000]"'
				 sFieldLabel   = $aLang.topic_create_text
				 sFieldClasses = 'width-full js-editor'}

		{* Preview *}
		<div class="text mb-20" id="text_preview" style="display: none;"></div>

		{hook run='form_add_talk_end'}

		{* Кнопки *}
		{include 'forms/fields/form.field.button.tpl' sFieldName='submit_talk_add' sFieldStyle='primary' sFieldText=$aLang.talk_create_submit}
		{include 'forms/fields/form.field.button.tpl' sFieldName='submit_preview' sFieldType='button' sFieldText=$aLang.common.preview_text sFieldAttributes='onclick="jQuery(\'#text_preview\').show(); ls.utils.textPreview($(\'#talk_text\'), $(\'#text_preview\'), false); return false;"'}
	</form>

	{hook run='talk_add_end'}
{/block}