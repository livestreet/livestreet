{include file='forms/editor.init.tpl'}


<form action="" method="POST">
	{hook run='plugin_page_form_add_begin'}
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="page_pid">{$aLang.plugin.page.create_parent_page}</label>
	<select name="page_pid" id="page_pid" class="width-300">
		<option value="0"></option>
		{foreach $aPages as $oPage}
			<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
		{/foreach}
	</select></p>

	<p><label for="page_title">{$aLang.plugin.page.create_title}:</label>
	<input type="text" id="page_title" class="width-full" name="page_title" value="{$_aRequest.page_title}" /></p>

	<p><label for="page_url">{$aLang.plugin.page.create_url}:</label>
	<input type="text" class="width-full" id="page_url" name="page_url" value="{$_aRequest.page_url}" /></p>

	<label for="page_text">{$aLang.plugin.page.create_text}:</label>
	<textarea name="page_text" id="page_text" rows="20" class="js-editor width-full">{$_aRequest.page_text}</textarea><br />

	<p><label for="page_seo_keywords">{$aLang.plugin.page.create_seo_keywords}:</label>
	<input type="text" class="width-full" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" />
	<span class="note">{$aLang.plugin.page.create_seo_keywords_notice}</span></p>

	<p><label for="page_seo_description">{$aLang.plugin.page.create_seo_description}:</label>
	<input type="text" class="width-full" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" />
	<span class="note">{$aLang.plugin.page.create_seo_description_notice}</span></p>

	<p><label for="page_sort">{$aLang.plugin.page.create_sort}:</label>
	<input type="text" id="page_sort" class="width-full" name="page_sort" value="{$_aRequest.page_sort}" />
	<span class="note">{$aLang.plugin.page.create_sort_notice}</span></p>

	<p>
		<label><input type="checkbox" id="page_auto_br" name="page_auto_br" value="1" {if $_aRequest.page_auto_br==1}checked{/if}/> {$aLang.plugin.page.create_auto_br}</label>
		<label><input type="checkbox" id="page_active" name="page_active" value="1" {if $_aRequest.page_active==1}checked{/if} /> {$aLang.plugin.page.create_active}</label>
		<label><input type="checkbox" id="page_main" name="page_main" value="1" {if $_aRequest.page_main==1}checked{/if} /> {$aLang.plugin.page.create_main}</label>
	</p>

	{hook run='plugin_page_form_add_end'}
	<p>
		<button type="submit" class="button button-primary" name="submit_page_save">{$aLang.plugin.page.create_submit_save}</button>
		<button name="submit_page_cancel" class="button" onclick="window.location='{router page='page'}admin/'; return false;" />{$aLang.plugin.page.create_submit_cancel}</button>
	</p>

	<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
</form>