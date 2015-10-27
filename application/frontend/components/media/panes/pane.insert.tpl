{extends './pane.tpl'}

{block 'media_pane_options' append}
    {$id = 'tab-media-insert'}
{/block}

{block 'media_pane_content'}
    {component 'media' template='uploader'
        attributes   = [ 'id' => 'media-uploader' ]
        classes      = 'js-media-uploader'
        targetParams = $aTargetParams
        targetType   = $sMediaTargetType
        targetId     = $sMediaTargetId
        targetTmp    = $sMediaTargetTmp}
{/block}

{block 'media_pane_footer' prepend}
    {component 'button'
        mods    = 'primary'
        classes = 'js-media-insert-submit'
        text    = {lang name='media.insert.submit'}}
{/block}