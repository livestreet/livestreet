{if $sEvent=='add'}
	{include file='header.tpl' menu='topic_action'}
{else}
	{include file='header.tpl'}
{/if}


{if $sEvent=='add'}
	<h2>{$aLang.blog_create}</h2>
{else}
	{include file='menu.blog_edit.tpl'}
{/if}

<form action="" method="POST" enctype="multipart/form-data">
	{hook run='form_add_blog_begin'}
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="blog_title">{$aLang.blog_create_title}:</label><br />
	<input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" class="input-wide" /><br />
	<span class="note">{$aLang.blog_create_title_notice}</span></p>

	<p><label for="blog_url">{$aLang.blog_create_url}:</label><br />
	<input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" class="input-wide" {if $_aRequest.blog_id}disabled{/if} /><br />
	<span class="note">{$aLang.blog_create_url_notice}</span></p>

	<p><label for="blog_type">{$aLang.blog_create_type}:</label><br />
	<select name="blog_type" id="blog_type" class="input-200">
		<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
		<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
	</select><br />
	<span class="note">{$aLang.blog_create_type_open_notice}</span></p>

	<p><label for="blog_description">{$aLang.blog_create_description}:</label><br />
	<textarea name="blog_description" id="blog_description" rows="20" class="input-wide">{$_aRequest.blog_description}</textarea><br />
	<span class="note">{$aLang.blog_create_description_notice}</span></p>

	<p><label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label><br />
	<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" class="input-100" /><br />
	<span class="note">{$aLang.blog_create_rating_notice}</span></p>

	<p>
	{if $oBlogEdit and $oBlogEdit->getAvatar()}
		<img src="{$oBlogEdit->getAvatarPath(48)}" />
		<img src="{$oBlogEdit->getAvatarPath(24)}" />
		<label><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; {$aLang.blog_create_avatar_delete}</label><br /><br />
	{/if}
	<label for="avatar">{$aLang.blog_create_avatar}:</label><br />
	<input type="file" name="avatar" id="avatar"></p>

	{hook run='form_add_blog_end'}

	<p><input type="submit" name="submit_blog_add" value="{$aLang.blog_create_submit}" />
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}" /></p>
</form>


{include file='footer.tpl'}