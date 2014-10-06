{**
 * Media content
 *}

<div class="media clearfix {$smarty.local.classes}">
	{* Боковое меню *}
	{include 'components/nav/nav.tabs.tpl' sName='media' sClasses='media-nav js-media-tabs' aItems=[
		[ 'name' => 'insert',   'pane' => 'tab-media-insert',   'text' => {lang name='media.nav.insert'},   'classes' => 'js-tab-show-gallery active', 'attributes' => 'data-media-name="insert"' ],
		[ 'name' => 'photoset', 'pane' => 'tab-media-photoset', 'text' => {lang name='media.nav.photoset'}, 'classes' => 'js-tab-show-gallery',        'attributes' => 'data-media-name="photoset"' ],
		[ 'name' => 'url',      'pane' => 'tab-media-url',      'text' => {lang name='media.nav.url'}, 'attributes' => 'data-media-name="url"' ]
	]}

	{* Содержимое табов *}
	<div data-type="tab-panes" class="media-panes">
		{include './panes/pane.insert.tpl' isActive=true}
		{include './panes/pane.url.tpl'}
		{include './panes/pane.photoset.tpl'}
	</div>
</div>