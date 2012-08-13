{include file='window_load_img.tpl' sToLoad='page_text'}


{if $oConfig->GetValue('view.tinymce')}
	<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce-jq/tiny_mce.js"></script>
	{literal}
		<script type="text/javascript">
			jQuery(function($){
				tinyMCE.init(ls.settings.getTinymce());
			});
		</script>
	{/literal}

{else}
	{include file='window_load_img.tpl' sToLoad='page_text'}
	<script type="text/javascript">
		jQuery(function($){
			ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
			// Подключаем редактор
			$('#page_text').markItUp(ls.settings.getMarkitup());
		});
	</script>
{/if}


<form action="" method="POST">
	{hook run='plugin_page_form_add_begin'}
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="page_pid">{$aLang.plugin.page.create_parent_page}</label>
	<select name="page_pid" id="page_pid" class="input-width-300">
		<option value="0"></option>
		{foreach from=$aPages item=oPage}
			<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
		{/foreach}
	</select></p>


	<p><label for="page_title">{$aLang.plugin.page.create_title}:</label>
	<input type="text" id="page_title" class="input-text input-width-full" name="page_title" value="{$_aRequest.page_title}" class="input-wide" />	</p>


	<p><label for="page_url">{$aLang.plugin.page.create_url}:</label>
	<input type="text" class="input-text input-width-full" id="page_url" name="page_url" value="{$_aRequest.page_url}" class="input-wide" />	</p>


	<label for="page_text">{$aLang.plugin.page.create_text}:</label>
	<textarea name="page_text" id="page_text" rows="20" class="mce-editor input-width-full">{$_aRequest.page_text}</textarea><br />

	<p><label for="page_seo_keywords">{$aLang.plugin.page.create_seo_keywords}:</label>
	<input type="text" class="input-text input-width-full" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" class="input-wide" />
	<span class="note">{$aLang.plugin.page.create_seo_keywords_notice}</span></p>

	<p><label for="page_seo_description">{$aLang.plugin.page.create_seo_description}:</label>
	<input type="text" class="input-text input-width-full" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" class="input-wide" />
	<span class="note">{$aLang.plugin.page.create_seo_description_notice}</span></p>

	<p><label for="page_sort">{$aLang.plugin.page.create_sort}:</label>
	<input type="text" id="page_sort" class="input-text input-width-full" name="page_sort" value="{$_aRequest.page_sort}" class="input-wide" />
	<span class="note">{$aLang.plugin.page.create_sort_notice}</span></p>

	<p>
		<label><input type="checkbox" id="page_auto_br" name="page_auto_br" value="1" class="input-checkbox" {if $_aRequest.page_auto_br==1}checked{/if}/> {$aLang.plugin.page.create_auto_br}</label>
		<label><input type="checkbox" id="page_active" name="page_active" value="1" class="input-checkbox" {if $_aRequest.page_active==1}checked{/if} /> {$aLang.plugin.page.create_active}</label>
		<label><input type="checkbox" id="page_main" name="page_main" value="1" class="input-checkbox" {if $_aRequest.page_main==1}checked{/if} /> {$aLang.plugin.page.create_main}</label>
	</p>

	{hook run='plugin_page_form_add_end'}
	<p>
		<button type="submit" class="button button-primary" name="submit_page_save">{$aLang.plugin.page.create_submit_save}</button>
		<button name="submit_page_cancel" class="button" onclick="window.location='{router page='page'}admin/'; return false;" />{$aLang.plugin.page.create_submit_cancel}</button>
	</p>

	<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
</form>