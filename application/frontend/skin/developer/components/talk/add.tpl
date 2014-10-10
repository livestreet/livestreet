{**
 * Форма создания личного сообщения
 *}

{hook run='talk_add_begin'}

<form action="" method="POST" enctype="multipart/form-data" class="js-form-validate">
	{hook run='form_add_talk_begin'}

	{include 'components/field/field.hidden.security_key.tpl'}

	{* Получатели *}
	{include 'components/field/field.text.tpl'
			 sName    = 'talk_users'
			 aRules   = [ 'required' => true, 'rangetags' => '[1,99]' ]
			 sLabel   = $aLang.talk.add.fields.users.label
			 sInputClasses = 'autocomplete-users-sep js-input-talk-users'
			 sNote    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\" data-param-target=\".js-input-talk-users\">{lang 'talk.add.choose_friends'}</a>"}

	{* Заголовок *}
	{include 'components/field/field.text.tpl'
			 sName    = 'talk_title'
			 aRules   = [ 'required' => true, 'rangelength' => '[2,200]' ]
			 sLabel   = $aLang.talk.add.fields.title.label}

	{* Текст сообщения *}
	{include 'components/editor/editor.tpl'
			sSet             = 'light'
			sMediaTargetType = 'talk'
			sName            = 'talk_text'
			aRules           = [ 'required' => true, 'rangelength' => '[2,3000]' ]
			sLabel           = $aLang.talk.add.fields.text.label
			sInputClasses    = 'js-editor'}

	{* Preview *}
	<div class="text mb-20" id="text_preview" style="display: none;"></div>

	{hook run='form_add_talk_end'}

	{* Кнопки *}
	{include 'components/button/button.tpl' name='submit_talk_add' mods='primary' text=$aLang.common.send}
	{include 'components/button/button.tpl'
		name       ='submit_preview'
		type       ='button'
		text       =$aLang.common.preview_text
		attributes ='onclick="jQuery(\'#text_preview\').show(); ls.utils.textPreview($(\'#talk_text\'), $(\'#text_preview\'), false); return false;"'}
</form>

{hook run='talk_add_end'}