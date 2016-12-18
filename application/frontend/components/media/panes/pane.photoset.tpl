{extends './pane.tpl'}

{block 'media_pane_options' append}
    {$id = 'tab-media-photoset'}
{/block}

{block 'media_pane_footer' prepend}
    {component 'button'
        mods    = 'primary'
        classes = 'js-media-photoset-submit'
        text    = {lang name='media.photoset.submit'}}
{/block}