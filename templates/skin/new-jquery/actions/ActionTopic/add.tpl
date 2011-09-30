{include file='header.tpl' menu='topic_action'}


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
		ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
		// Подключаем редактор		
		$('#topic_text').markItUp(getMarkitupSettings());
	});
	</script>
{/if}



<div class="topic" style="display: none;">
	<div class="content" id="text_preview"></div>
</div>


{if $sEvent=='add'}
	<h2>{$aLang.topic_topic_create}</h2>
{else}
	<h2>{$aLang.topic_topic_edit}</h2>
{/if}

{hook run='add_topic_topic_begin'}
<form action="" method="POST" enctype="multipart/form-data">
	{hook run='form_add_topic_topic_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="blog_id">{$aLang.topic_create_blog}</label><br />
	<select name="blog_id" id="blog_id" onChange="ls.blog.loadInfo($(this).val());" class="input-wide">
		<option value="0">{$aLang.topic_create_blog_personal}</option>
		{foreach from=$aBlogsAllow item=oBlog}
			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()|escape:'html'}</option>
		{/foreach}
	</select></p>

	<script language="JavaScript" type="text/javascript">
		jQuery(document).ready(function($){
			ls.blog.loadInfo($('#blog_id').val());
		});
    </script>
	
	<p><label for="topic_title">{$aLang.topic_create_title}:</label><br />
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-wide" /><br />
	<span class="note">{$aLang.topic_create_title_notice}</span></p>

	<label for="topic_text">{$aLang.topic_create_text}{if !$oConfig->GetValue('view.tinymce')} ({$aLang.topic_create_text_notice}){/if}:</label>
	<textarea name="topic_text" id="topic_text" rows="20" class="input-wide">{$_aRequest.topic_text}</textarea><br />

	<p><label for="topic_tags">{$aLang.topic_create_tags}:</label><br />
	<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-wide autocomplete-tags-sep" /><br />
	<span class="note">{$aLang.topic_create_tags_notice}</span></p>

	<p><label for="topic_forbid_comment"><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if} />
	{$aLang.topic_create_forbid_comment}</label><br />
	<span class="note">{$aLang.topic_create_forbid_comment_notice}</span></p>

	{if $oUserCurrent->isAdministrator()}
		<p><label for="topic_publish_index"><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if} />
		{$aLang.topic_create_publish_index}</label><br />
		<span class="note">{$aLang.topic_create_publish_index_notice}</span></p>
	{/if}

	{hook run='form_add_topic_topic_end'}

	<p class="buttons">
		<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
		<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="jQuery('#text_preview').parent().show(); ls.tools.textPreview('topic_text',false); return false;" />&nbsp;
		<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
	</p>	
</form>
{hook run='add_topic_topic_end'}


{include file='footer.tpl'}