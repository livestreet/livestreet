{**
 * Опции вставки
 *
 * @param boolean $useSizes
 *}

{component_define_params params=[ 'useSizes' ]}

{capture 'block_content'}
    <form method="post" action="" enctype="multipart/form-data">
        {* Выравнивание *}
        {component 'field' template='select'
            name  = 'align'
            label = {lang name='media.image_align.title'}
            items = [
                [ 'value' => '',       'text' => {lang name='media.image_align.no'} ],
                [ 'value' => 'left',   'text' => {lang name='media.image_align.left'} ],
                [ 'value' => 'right',  'text' => {lang name='media.image_align.right'} ],
                [ 'value' => 'center', 'text' => {lang name='media.image_align.center'} ]
            ]}

        {* Размер *}
        {if $useSizes|default:true}
            {component 'field' template='select'
                name          = 'size'
                label         = {lang name='media.insert.settings.fields.size.label'}
                items         = [[ 'value' => 'original', 'text' => {lang name='media.insert.settings.fields.size.original'} ]]}
        {/if}
    </form>
{/capture}

{component 'uploader' template='block'
    title      = {lang 'media.insert.settings.title'}
    content    = $smarty.capture.block_content
    classes    = 'js-media-info-block js-media-info-block-image-options'
    attributes = [ 'data-type' => 'insert', 'data-filetype' => '1' ]}