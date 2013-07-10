{**
 * Инициализация редактора
 *
 * sEditorType - тип
 * sEditorSelector - селектор textarea с редактором
 *
 * Настройки редакторов храняться в файле templates/framework/js/core/settings.js
 *
 * TODO: Исправить повторное подключение скрипта
 * TODO: Локализация TinyMCE
 *}

{* Дефолтный тип редактора *}
{if ! $sEditorType}
	{$sEditorType = 'default'}
{/if}

{* Дефолтный селектор редактора *}
{if ! $sEditorSelector}
	{$sEditorSelector = 'js-editor'}
{/if}

{* Инициализация *}
{if $oConfig->GetValue('view.wysiwyg')}
	{* WYSIWYG редактор *}

	{hookb run='editor_init_wysiwyg' sEditorType=$sEditorType sEditorSelector=$sEditorSelector}
		{if $sEditorType == 'comment'}
			{$sSettings = 'ls.settings.get("tinymceComment")'}
		{else}
			{hook run='editor_init_wysiwyg_settings' sEditorType=$sEditorType assign='sSettings'}
			
			{if ! $sSettings}
				{$sSettings = 'ls.settings.get("tinymce")'}
			{/if}
		{/if}

		<script src="{cfg name='path.static.framework'}/js/vendor/tinymce/tiny_mce.js"></script>

		<script>
			jQuery(function($) {
				tinyMCE.init($.extend({ }, {$sSettings}, { 
					editor_selector : '{$sEditorSelector}',
					language : {if $oConfig->GetValue('lang.current') == 'russian'}'ru'{else}'en'{/if}
				}));
			});
		</script>
	{/hookb}
{else}
	{* Markup редактор *}

	{hookb run='editor_init_markup' sEditorType=$sEditorType sEditorSelector=$sEditorSelector}
		{include file='modals/modal.upload_image.tpl'}

		{if $sEditorType == 'comment'}
			{$sSettings = 'ls.settings.get("markitupComment")'}
		{else}
			{hook run='editor_init_markup_settings' sEditorType=$sEditorType assign='sSettings'}
			
			{if ! $sSettings}
				{$sSettings = 'ls.settings.get("markitup")'}
			{/if}
		{/if}
		
		<script src="{cfg name='path.static.framework'}/js/vendor/markitup/jquery.markitup.js"></script>

		<script>
			jQuery(function($) {
				ls.lang.load({lang_load name="panel_b,panel_i,panel_u,panel_s,panel_url,panel_url_promt,panel_code,panel_video,panel_image,panel_cut,panel_quote,panel_list,panel_list_ul,panel_list_ol,panel_title,panel_clear_tags,panel_video_promt,panel_list_li,panel_image_promt,panel_user,panel_user_promt"});
				
				$('.{$sEditorSelector}').markItUp({$sSettings});
			});
		</script>
	{/hookb}
{/if}