{if $sEvent=='add'}
	{include file='header.tpl' menu='topic_action'}
{else}
	{include file='header.tpl' menu='blog_edit'}
{/if}

	{if $sEvent=='add'}
		<h2>{$aLang.blog_create}</h2>
	{else}
		<h2>{$aLang.blog_admin}: <a href="{router page='blog'}{$oBlogEdit->getUrl()}/">{$oBlogEdit->getTitle()}</a></h2>
	{/if}

	<form action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	
		<p><label for="blog_title">{$aLang.blog_create_title}:</label>
		<input type="text" id="blog_title" name="blog_title" class="w100p" value="{$_aRequest.blog_title}" />
		<span class="form-note">{$aLang.blog_create_title_notice}</span></p>

		<p><label for="blog_url">{$aLang.blog_create_url}:</label>
		<input type="text" id="blog_url" name="blog_url" class="w100p" value="{$_aRequest.blog_url}" {if $_aRequest.blog_id}disabled{/if} />
		<span class="form-note">{$aLang.blog_create_url_notice}</span></p>
		
		<p><label for="blog_type">{$aLang.blog_create_type}:</label>
		<select name="blog_type" id="blog_type" onChange="">
			<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
			<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
		</select>
		<span class="form-note">{$aLang.blog_create_type_notice}</span></p>

		<p><label for="blog_description">{$aLang.blog_create_description}:</label>
		<textarea name="blog_description" id="blog_description" rows="7">{$_aRequest.blog_description}</textarea>
		<span class="form-note">{$aLang.blog_create_description_notice}</span></p>
		
		<p><label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label>
		<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" class="w100p" value="{$_aRequest.blog_limit_rating_topic}" />
		<span class="form-note">{$aLang.blog_create_rating_notice}</span></p>
			
		<p>
		{if $oBlogEdit and $oBlogEdit->getAvatar()}
			<img src="{$oBlogEdit->getAvatarPath(48)}" />
			<img src="{$oBlogEdit->getAvatarPath(24)}" />
			<label for="avatar_delete"><input type="checkbox" id="avatar_delete" name="avatar_delete" class="input-checkbox" value="on" /> &mdash; {$aLang.blog_create_avatar_delete}</label><br />
		{/if}
		<label for="avatar">{$aLang.blog_create_avatar}:</label>
		<input type="file" name="avatar" id="avatar"></p>
						
		<p><input type="submit" name="submit_blog_add" value="{$aLang.blog_create_submit}">						
		<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}"></p>
	</form>

{include file='footer.tpl'}