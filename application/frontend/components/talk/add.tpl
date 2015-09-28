{**
 * Форма создания личного сообщения
 *}

{hook run='talk_add_begin'}

<form action="" method="POST" enctype="multipart/form-data" class="js-form-validate">
	{hook run='form_add_talk_begin'}

	{component 'field' template='hidden.security-key'}

	{* Получатели *}
	{component 'user' template='choose'
	    name         = 'talk_users[]'
		rules        = [ 'required' => true, 'rangetags' => '[1,99]' ]
	    classes      = 'js-talk-add-user-choose'
	    label        = {lang 'talk.add.fields.users.label'}
	    lang_choose  = {lang 'talk.add.choose_friends'}}

	{* Заголовок *}
	{component 'field' template='text'
			 name    = 'talk_title'
			 rules   = [ 'required' => true, 'length' => '[2,200]' ]
			 label   = $aLang.talk.add.fields.title.label}

	{* Текст сообщения *}
	{component 'editor'
			sSet             = 'light'
			sMediaTargetType = 'talk'
			id	             = 'talk_text'
			name             = 'talk_text'
			rules            = [ 'required' => true, 'length' => '[2,3000]' ]
			label            = $aLang.talk.add.fields.text.label
			inputClasses     = 'js-editor-default'}

	{* Preview *}
	<div class="ls-text mb-20" id="text_preview" style="display: none;"></div>

	{hook run='form_add_talk_end'}

	{* Кнопки *}
	{component 'button' name='submit_talk_add' mods='primary' text=$aLang.common.send}
	{component 'button'
		name       = 'submit_preview'
		type       = 'button'
		text       = $aLang.common.preview_text
		attributes = [ 'onclick' => "jQuery('#text_preview').show(); ls.utils.textPreview($('#talk_text'), $('#text_preview'), false); return false;" ]}
</form>

{hook run='talk_add_end'}