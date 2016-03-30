{**
 * Media content
 *}

<div class="ls-media ls-clearfix {$smarty.local.classes}">
    {component 'tabs' classes='ls-media-nav js-media-nav' mods='align-left' tabs=[
        [ 'text' => {lang 'media.nav.insert'},   'body' => {component 'media' template='pane.insert'},   'classes' => 'js-tab-show-gallery', 'attributes' => [ 'data-media-name' => 'insert' ] ],
        [ 'text' => {lang 'media.nav.photoset'}, 'body' => {component 'media' template='pane.photoset'}, 'classes' => 'js-tab-show-gallery', 'attributes' => [ 'data-media-name' => 'photoset' ] ],
        [ 'text' => {lang 'media.nav.url'},      'body' => {component 'media' template='pane.url'},      'attributes' => [ 'data-media-name' => 'url' ] ]
    ]}
</div>