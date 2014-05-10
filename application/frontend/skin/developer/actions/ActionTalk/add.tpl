{**
 * Создание личного сообщения
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
	{include 'forms/editor.init.tpl' sEditorType='comment' sMediaTargetType='talk'}

	{hook run='talk_add_begin'}

	<form action="" method="POST" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_add_talk_begin'}

		{include 'components/field/field.hidden.security_key.tpl'}

		{* Получатели *}
		{include 'components/field/field.text.tpl'
				 sName    = 'talk_users'
				 aRules   = [ 'required' => true, 'rangetags' => '[1,99]' ]
				 sLabel   = $aLang.talk_create_users
				 sClasses = 'width-full autocomplete-users-sep'
				 sNote    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\" data-param-target=\".js-input-talk_users\">Выбрать из списка друзей</a>"}

		{* Заголовок *}
		{include 'components/field/field.text.tpl'
				 sName    = 'talk_title'
				 aRules   = [ 'required' => true, 'rangelength' => '[2,200]' ]
				 sLabel   = $aLang.talk_create_title}

		{* Текст сообщения *}
		{include 'components/field/field.textarea.tpl'
				 sName    = 'talk_text'
				 aRules   = [ 'required' => true, 'rangelength' => '[2,3000]' ]
				 sLabel   = $aLang.topic_create_text
				 sInputClasses = 'js-editor'}

		{* Preview *}
		<div class="text mb-20" id="text_preview" style="display: none;"></div>

		{hook run='form_add_talk_end'}

		{* Кнопки *}
		{include 'components/button/button.tpl' sName='submit_talk_add' sStyle='primary' sText=$aLang.talk_create_submit}
		{include 'components/button/button.tpl' sName='submit_preview' sType='button' sText=$aLang.common.preview_text sAttributes='onclick="jQuery(\'#text_preview\').show(); ls.utils.textPreview($(\'#talk_text\'), $(\'#text_preview\'), false); return false;"'}
	</form>

	{hook run='talk_add_end'}
{/block}