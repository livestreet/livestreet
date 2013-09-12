{**
 * Создание личного сообщения
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{**
	 * Выбор адресата среди друзей на странице создания личного сообщения
	 *}
	<div class="accordion">
		<h3 class="accordion-header" onclick="jQuery('#block_talk_friends_content').toggle(); return false;"><span class="link-dotted">{$aLang.block_friends}</span></h3>

		<div class="accordion-content" id="block_talk_friends_content">
			{if $aUsersFriend}
				<ul class="list" id="friends">
					{foreach $aUsersFriend as $oFriend}
						<li>
							<input id="talk_friend_{$oFriend->getId()}" type="checkbox" name="friend[{$oFriend->getId()}]" class="input-checkbox" /> 
							<label for="talk_friend_{$oFriend->getId()}" id="talk_friend_{$oFriend->getId()}_label">{$oFriend->getLogin()}</label>
						</li>
					{/foreach}
				</ul>
				
				<footer>
					<a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | 
					<a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a>
				</footer>
			{else}
				{include file='alert.tpl' mAlerts=$aLang.block_friends_empty sAlertStyle='empty'}
			{/if}
		</div>
	</div>

	{hook run='talk_add_begin'}

	{include file='forms/editor.init.tpl' sEditorType='comment'}

	<form action="" method="POST" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_add_talk_begin'}
		
		{include file='forms/form.field.hidden.security_key.tpl'}

		{* Получатели *}
		{include file='forms/form.field.text.tpl'
				 sFieldName    = 'talk_users'
				 sFieldRules   = 'required="true" rangetags="[1,99]"'
				 sFieldLabel   = $aLang.talk_create_users
				 sFieldClasses = 'width-full autocomplete-users-sep'}

		{* Заголовок *}
		{include file='forms/form.field.text.tpl'
				 sFieldName    = 'talk_title'
				 sFieldRules   = 'required="true" rangelength="[2,200]"'
				 sFieldLabel   = $aLang.talk_create_title}

		{* Текст сообщения *}	 
		{include file='forms/form.field.textarea.tpl'
				 sFieldName    = 'talk_text'
				 sFieldRules   = 'required="true" rangelength="[2,3000]"'
				 sFieldLabel   = $aLang.topic_create_text
				 sFieldClasses = 'width-full js-editor'}

		{* Preview *}
		<div class="text mb-20" id="text_preview" style="display: none;"></div>
		
		{hook run='form_add_talk_end'}
		
		{* Кнопки *}
		{* TODO: js *}
		{include file='forms/form.field.button.tpl' sFieldName='submit_talk_add' sFieldStyle='primary' sFieldText=$aLang.talk_create_submit}
		{include file='forms/form.field.button.tpl' sFieldName='submit_preview' sFieldType='button' sFieldText=$aLang.topic_create_submit_save sFieldAttributes='onclick="jQuery(\'#text_preview\').show(); ls.tools.textPreview(\'talk_text\',false); return false;"'}
	</form>

	{hook run='talk_add_end'}
{/block}