{**
 * Кроп фотографии
 *}

{extends 'Component@crop.crop'}

{block 'modal_options' append}
    {$title = {lang 'user.photo.crop_photo.title'}}
    {$desc = {lang 'user.photo.crop_photo.desc'}}
    {$usePreview = false}
{/block}

{block 'modal_footer_begin'}
    {component 'button' text={lang 'user.photo.crop_photo.submit'} classes='js-crop-submit' mods='primary'}
{/block}