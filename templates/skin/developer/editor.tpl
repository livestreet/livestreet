{if $oConfig->GetValue('view.tinymce')}
	{if !$sSettingsTinymce}
		{assign var="sSettingsTinymce" value="ls.settings.getTinymce()"}
	{/if}

	<script src="{cfg name='path.static.framework'}/js/vendor/tinymce/tiny_mce.js"></script>
	<script type="text/javascript">
		jQuery(function($){
			tinyMCE.init({$sSettingsTinymce});
		});
	</script>
{else}
	{include file='modals/modal.load_img.tpl'}

	{if !$sSettingsMarkitup}
		{assign var="sSettingsMarkitup" value="ls.settings.getMarkitup()"}
	{/if}
	<script type="text/javascript">
		jQuery(function($){
			ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
			// Подключаем редактор
			$('.markitup-editor').markItUp({$sSettingsMarkitup});
		});
	</script>
{/if}