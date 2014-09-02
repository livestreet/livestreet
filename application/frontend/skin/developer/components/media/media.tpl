{* Массив со значениями для селекта Выравнивание *}
{$aSelectImageAlign = [
	[ 'value' => '',       'text' => $aLang.uploadimg_align_no ],
	[ 'value' => 'left',   'text' => $aLang.uploadimg_align_left ],
	[ 'value' => 'right',  'text' => $aLang.uploadimg_align_right ],
	[ 'value' => 'center', 'text' => $aLang.uploadimg_align_center ]
]}

{$aTargetParams = $LS->Media_GetTargetTypeParams( $sMediaTargetType )}

<div class="grid-row">
	{* Боковое меню *}
	{include 'components/nav/nav.tabs.tpl' sName='media' sClasses='media-nav' aItems=[
		[ 'name' => 'insert',   'pane' => 'tab-media-insert',   'text' => {lang name='media.nav.insert'},   'classes' => 'js-tab-show-gallery active', 'attributes' => 'data-media-mode="insert"' ],
		[ 'name' => 'photoset', 'pane' => 'tab-media-photoset', 'text' => {lang name='media.nav.photoset'}, 'classes' => 'js-tab-show-gallery',        'attributes' => 'data-media-mode="photoset"' ],
		[ 'name' => 'url',      'pane' => 'tab-media-url',      'text' => {lang name='media.nav.url'} ],
		[ 'name' => 'preview',  'pane' => 'tab-media-preview',  'text' => {lang name='media.nav.preview'}, 'is_enabled' => $aTargetParams.allow_preview ]
	]}

	{* Содержимое табов *}
	<div data-type="tab-panes" class="modal-upload-image-content">
		{include './panes/pane.insert.tpl' isActive=true}
		{include './panes/pane.url.tpl'}
		{include './panes/pane.photoset.tpl'}
		{include './panes/pane.preview.tpl'}
	</div>
</div>