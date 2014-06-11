{**
 * Тулбар
 * Кнопка обновления комментариев
 *
 * @styles css/toolbar.css
 * @scripts js/livestreet/toolbar.js
 *}

{extends 'components/toolbar/toolbar.item.tpl'}

{block 'toolbar_item_options' append}
	{$_sMods = 'comments'}
	{$_bShow = !! $oUserCurrent}
	{$_sClasses = 'js-toolbar-comments'}
	{$_sAttributes = 'data-target=".js-comment"'}
{/block}

{block 'toolbar_item'}
	<div class="toolbar-comments-update js-toolbar-comments-update"><i></i></div>
	<div class="toolbar-comments-count js-toolbar-comments-count" style="display: none;" title="{$aLang.comments.comment.count_new}"></div>
{/block}