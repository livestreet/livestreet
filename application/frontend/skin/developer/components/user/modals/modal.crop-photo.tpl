{**
 * Кроп фотографии
 *}

{extends 'components/crop/crop.tpl'}

{block 'modal_options' append}
    {$title = {lang 'user.photo.crop_photo.title'}}
    {$desc = {lang 'user.photo.crop_photo.desc'}}
    {$usePreview = false}
{/block}

{block 'modal_footer_begin'}
    {include 'components/button/button.tpl' text={lang 'user.photo.crop_photo.submit'} classes='js-crop-submit' mods='primary'}
{/block}