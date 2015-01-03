{**
 * Media content
 *}

<div class="media clearfix {$smarty.local.classes}">
	{include './panes/pane.insert.tpl' assign=media_tab_insert}
	{include './panes/pane.photoset.tpl' assign=media_tab_photoset}
	{include './panes/pane.url.tpl' assign=media_tab_url}

    {component 'tabs' classes='media-nav js-tabs-auth' mods='align-left' tabs=[
        [ 'text' => {lang 'media.nav.insert'},   'content' => $media_tab_insert,   'classes' => 'js-tab-show-gallery', 'attributes' => 'data-media-name="insert"' ],
        [ 'text' => {lang 'media.nav.photoset'}, 'content' => $media_tab_photoset, 'classes' => 'js-tab-show-gallery', 'attributes' => 'data-media-name="photoset"' ],
        [ 'text' => {lang 'media.nav.url'},      'content' => $media_tab_url, 'attributes' => 'data-media-name="url"' ]
    ]}
</div>