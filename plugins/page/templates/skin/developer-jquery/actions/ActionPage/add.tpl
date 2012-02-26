{include file='window_load_img.tpl' sToLoad='page_text'}

<link rel="stylesheet" type="text/css" href="{$sTemplateWebPathPlugin}css/style.css" media="all" />


{if $oConfig->GetValue('view.tinymce')}
	<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>
	{literal}
		<script type="text/javascript">
		jQuery(function($){
			tinyMCE.init({
				mode : "textareas",
				theme : "advanced",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_buttons1 : "lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,code",
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
				plugins : "lseditor,safari,inlinepopups,media",
				convert_urls : false,
				extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
				language : TINYMCE_LANG
			});
		});
		</script>
	{/literal}

{else}
	{include file='window_load_img.tpl' sToLoad='page_text'}
	<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li"});
		// Подключаем редактор
		$('#page_text').markItUp(getMarkitupSettings());
	});
	</script>
{/if}


<form action="" method="POST">
	{hook run='plugin_page_form_add_begin'}
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="page_pid">{$aLang.page_create_parent_page}</label><br />
	<select name="page_pid" id="page_pid" class="input-300">
		<option value="0"></option>
		{foreach from=$aPages item=oPage}
			<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
		{/foreach}
	</select></p>


	<p><label for="page_title">{$aLang.page_create_title}:</label><br />
	<input type="text" id="page_title" name="page_title" value="{$_aRequest.page_title}" class="input-wide" />	</p>


	<p><label for="page_url">{$aLang.page_create_url}:</label><br />
	<input type="text" id="page_url" name="page_url" value="{$_aRequest.page_url}" class="input-wide" />	</p>


	<label for="topic_text">{$aLang.page_create_text}:</label>
	<textarea name="page_text" id="page_text" rows="20" class="input-wide">{$_aRequest.page_text}</textarea><br /><br />

	<p><label for="page_seo_keywords">{$aLang.page_create_seo_keywords}:</label><br />
	<input type="text" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" class="input-wide" />
	<span class="note">{$aLang.page_create_seo_keywords_notice}</span></p>

	<p><label for="page_seo_description">{$aLang.page_create_seo_description}:</label><br />
	<input type="text" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" class="input-wide" />
	<span class="note">{$aLang.page_create_seo_description_notice}</span></p>

	<p><label for="page_sort">{$aLang.page_create_sort}:</label><br />
	<input type="text" id="page_sort" name="page_sort" value="{$_aRequest.page_sort}" class="input-wide" />
	<span class="note">{$aLang.page_create_sort_notice}</span></p>

	<p><label><input type="checkbox" id="page_auto_br" name="page_auto_br" value="1" class="checkbox" {if $_aRequest.page_auto_br==1}checked{/if}/>{$aLang.page_create_auto_br}</label></p>

	<p><label><input type="checkbox" id="page_active" name="page_active" value="1" class="checkbox" {if $_aRequest.page_active==1}checked{/if} />{$aLang.page_create_active}</label></p>

	<p><label><input type="checkbox" id="page_main" name="page_main" value="1" class="checkbox" {if $_aRequest.page_main==1}checked{/if} />{$aLang.page_create_main}</label></p>

	{hook run='plugin_page_form_add_end'}
	<p>
		<input type="submit" name="submit_page_save" value="{$aLang.page_create_submit_save}" />
		<input type="submit" name="submit_page_cancel" value="{$aLang.page_create_submit_cancel}" onclick="window.location='{router page='page'}admin/'; return false;" />
	</p>

	<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
</form>