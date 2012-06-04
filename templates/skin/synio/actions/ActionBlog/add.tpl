{if $sEvent=='add'}
	{include file='header.tpl' menu_content='create'}
{else}
	{include file='header.tpl'}
	{include file='menu.blog_edit.tpl'}
{/if}


{include file='editor.tpl' sImgToLoad='blog_description' sSettingsTinymce='ls.settings.getTinymceComment()' sSettingsMarkitup='ls.settings.getMarkitupComment()'}
	
<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.lang.load({lang_load name="blog_create_type_open_notice,blog_create_type_close_notice"});
		ls.blog.loadInfoType($('#blog_type').val());
	});
</script>


<form method="post" enctype="multipart/form-data" class="wrapper-content">
	{hook run='form_add_blog_begin'}
	
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	
	<p><label for="blog_title">{$aLang.blog_create_title}:</label>
	<input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" class="input-text input-width-full" />
	<small class="note">{$aLang.blog_create_title_notice}</small></p>

	
	<p><label for="blog_url">{$aLang.blog_create_url}:</label>
	<input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" class="input-text input-width-full" {if $_aRequest.blog_id and !$oUserCurrent->isAdministrator()}disabled{/if} />
	<small class="note">{$aLang.blog_create_url_notice}</small></p>
	

	<p><label for="blog_type">{$aLang.blog_create_type}:</label>
	<select name="blog_type" id="blog_type" class="input-width-200" onChange="ls.blog.loadInfoType(jQuery(this).val());">
		<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
		<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
	</select>
	<small class="note" id="blog_type_note">{$aLang.blog_create_type_open_notice}</small></p>

	
	<p><label for="blog_description">{$aLang.blog_create_description}:</label>
	<textarea name="blog_description" id="blog_description" rows="15" class="input-text input-width-full mce-editor markitup-editor input-width-full">{$_aRequest.blog_description}</textarea>
	<small class="note">{$aLang.blog_create_description_notice}</small></p>

	
	<p><label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label>
	<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" class="input-text input-width-100" />
	<small class="note">{$aLang.blog_create_rating_notice}</small></p>

	
	<p>
		{if $oBlogEdit and $oBlogEdit->getAvatar()}
			<div class="avatar-edit">
				{foreach from=$oConfig->GetValue('module.blog.avatar_size') item=iSize}
					{if $iSize}<img src="{$oBlogEdit->getAvatarPath({$iSize})}">{/if}
				{/foreach}
				
				<label><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on" class="input-checkbox"> {$aLang.blog_create_avatar_delete}</label>
			</div>
		{/if}
		
		<label for="avatar">{$aLang.blog_create_avatar}:</label>
		<input type="file" name="avatar" id="avatar">
	</p>

	
	{hook run='form_add_blog_end'}

	<button type="submit"  name="submit_blog_add" class="button button-primary">{$aLang.blog_create_submit}</button>
</form>


{include file='footer.tpl'}