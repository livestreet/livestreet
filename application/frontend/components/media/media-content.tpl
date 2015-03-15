{**
 * Media content
 *}

<div class="media clearfix {$smarty.local.classes}">
    {component 'tabs' classes='media-nav js-modal-tabs' mods='align-left' tabs=[
        [ 'text' => {lang 'media.nav.insert'},   'body' => {include './panes/pane.insert.tpl'},   'classes' => 'js-tab-show-gallery', 'attributes' => 'data-media-name="insert"' ],
        [ 'text' => {lang 'media.nav.photoset'}, 'body' => {include './panes/pane.photoset.tpl'}, 'classes' => 'js-tab-show-gallery', 'attributes' => 'data-media-name="photoset"' ],
        [ 'text' => {lang 'media.nav.url'},      'body' => {include './panes/pane.url.tpl'},      'attributes' => 'data-media-name="url"' ]
    ]}
</div>