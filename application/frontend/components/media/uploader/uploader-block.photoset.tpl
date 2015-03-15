{**
 * Опции фотосета
 *}

{capture 'block_content'}
    <form method="post" action="" enctype="multipart/form-data">
        {* Показывать ленту с превьюшками *}
        {component 'field' template='checkbox'
                 name    = 'use_thumbs'
                 checked = true
                 label   = {lang name='media.photoset.settings.fields.use_thumbs.label'}}

        {* Показывать описания фотографий *}
        {component 'field' template='checkbox'
                 name    = 'show_caption'
                 label   = {lang name='media.photoset.settings.fields.show_caption.label'}}
    </form>
{/capture}

{component 'uploader' template='block'
    title      = {lang 'media.photoset.settings.title'}
    content    = $smarty.capture.block_content
    classes    = 'js-media-info-block'
    attributes = [ 'data-type' => 'photoset' ]}