{**
 * Создание личного сообщения
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_content'}
	{**
	 * Выбор адресата среди друзей на странице создания личного сообщения
	 *}
	<div class="talk-search talk-friends" id="block_talk_search">
		<header>
			<a href="#" class="link-dotted close" onclick="ls.talk.toggleSearchForm(); return false;">{$aLang.block_friends}</a>
		</header>

		
		<div class="talk-search-content" id="block_talk_friends_content">
			{if $aUsersFriend}
				<ul class="friend-list" id="friends">
					{foreach $aUsersFriend as $oFriend}
						<li>
							<input id="talk_friend_{$oFriend->getId()}" type="checkbox" name="friend[{$oFriend->getId()}]" class="input-checkbox" /> 
							<label for="talk_friend_{$oFriend->getId()}" id="talk_friend_{$oFriend->getId()}_label">{$oFriend->getLogin()}</label>
						</li>
					{/foreach}
				</ul>
				
				<ul class="actions">
					<li><a href="#" id="friend_check_all" class="link-dotted">{$aLang.block_friends_check}</a></li>
					<li><a href="#" id="friend_uncheck_all" class="link-dotted">{$aLang.block_friends_uncheck}</a></li>
				</ul>
			{else}
				<div class="notice-empty">{$aLang.block_friends_empty}</div>
			{/if}
		</div>
	</div>

	{hook run='talk_add_begin'}

	{include file='forms/editor.init.tpl' sEditorType='comment'}

	<form action="" method="POST" enctype="multipart/form-data">
		{hook run='form_add_talk_begin'}
		
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

		<p><label for="talk_users">{$aLang.talk_create_users}:</label>
		<input type="text" class="input-text input-width-full autocomplete-users-sep" id="talk_users" name="talk_users" value="{$_aRequest.talk_users}" /></p>

		<p><label for="talk_title">{$aLang.talk_create_title}:</label>
		<input type="text" class="input-text input-width-full" id="talk_title" name="talk_title" value="{$_aRequest.talk_title}" /></p>

		<p><label for="talk_text">{$aLang.talk_create_text}:</label>
		<textarea name="talk_text" id="talk_text" rows="12" class="input-text input-width-full js-editor input-width-full">{$_aRequest.talk_text}</textarea></p>

		{* Preview *}
		<div class="text mb-20" id="text_preview" style="display: none;"></div>
		
		{hook run='form_add_talk_end'}
		
		<button type="submit"  class="button button-primary" name="submit_talk_add">{$aLang.talk_create_submit}</button>
		<button type="submit"  class="button" name="submit_preview" onclick="jQuery('#text_preview').show(); ls.tools.textPreview('talk_text',false); return false;">{$aLang.topic_create_submit_preview}</button>		
	</form>

	{hook run='talk_add_end'}
{/block}