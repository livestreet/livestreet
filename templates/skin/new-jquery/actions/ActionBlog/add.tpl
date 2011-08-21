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


{if $oConfig->GetValue('view.tinymce')}
	<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>

	<script type="text/javascript">
	{literal}
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_buttons1 : "lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,pagebreak,code",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : 0,
		theme_advanced_resizing_use_cookie : 0,
		theme_advanced_path : false,
		object_resizing : true,
		force_br_newlines : true,
		forced_root_block : '', // Needed for 3.x
		force_p_newlines : false,    
		plugins : "lseditor,safari,inlinepopups,media,pagebreak",
		convert_urls : false,
		extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
		pagebreak_separator :"<cut>",
		media_strict : false,
		language : TINYMCE_LANG,
		inline_styles:false,
		formats : {
			underline : {inline : 'u', exact : true},
			 strikethrough : {inline : 's', exact : true}
		}
	});
	{/literal}
	</script>
{else}
	{include file='window_load_img.tpl' sToLoad='topic_text'}
	<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li"});
		// Подключаем редактор		
		$('#blog_description').markItUp(getMarkitupSettings());
	});
	</script>
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
	<select name="blog_type" id="blog_type" class="input-200" onChange="ls.blog.loadInfoType(jQuery(this).val());">
		<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
		<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
	</select><br />
	<span class="note" id="blog_type_note">{$aLang.blog_create_type_open_notice}</span>
	<span id="blog_type_note_open" style="display:none;">{$aLang.blog_create_type_open_notice}</span>
	<span id="blog_type_note_close" style="display:none;">{$aLang.blog_create_type_close_notice}</span></p>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.blog.loadInfoType($('#blog_type').val());
	});
	</script>

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

	<input type="submit" name="submit_blog_add" value="{$aLang.blog_create_submit}" />
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}" />
</form>


{include file='footer.tpl'}