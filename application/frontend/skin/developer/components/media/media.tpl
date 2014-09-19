{* Массив со значениями для селекта Выравнивание *}
{$imageAlign = [
	[ 'value' => '',       'text' => {lang name='media.image_align.no'} ],
	[ 'value' => 'left',   'text' => {lang name='media.image_align.left'} ],
	[ 'value' => 'right',  'text' => {lang name='media.image_align.right'} ],
	[ 'value' => 'center', 'text' => {lang name='media.image_align.center'} ]
]}

{$aTargetParams = $LS->Media_GetTargetTypeParams( $sMediaTargetType )}

<div class="media clearfix {$smarty.local.classes}">
	{* Боковое меню *}
	{include 'components/nav/nav.tabs.tpl' sName='media' sClasses='media-nav' aItems=[
		[ 'name' => 'insert',   'pane' => 'tab-media-insert',   'text' => {lang name='media.nav.insert'},   'classes' => 'js-tab-show-gallery active', 'attributes' => 'data-media-mode="insert"' ],
		[ 'name' => 'photoset', 'pane' => 'tab-media-photoset', 'text' => {lang name='media.nav.photoset'}, 'classes' => 'js-tab-show-gallery',        'attributes' => 'data-media-mode="photoset"' ],
		[ 'name' => 'url',      'pane' => 'tab-media-url',      'text' => {lang name='media.nav.url'} ],
		[ 'name' => 'preview',  'pane' => 'tab-media-preview',  'text' => {lang name='media.nav.preview'}, 'is_enabled' => $aTargetParams.allow_preview ]
	]}

	{* Содержимое табов *}
	<div data-type="tab-panes" class="media-panes">
		{include './panes/pane.insert.tpl' isActive=true}
		{include './panes/pane.url.tpl'}
		{include './panes/pane.photoset.tpl'}
		{include './panes/pane.preview.tpl'}
	</div>
</div>