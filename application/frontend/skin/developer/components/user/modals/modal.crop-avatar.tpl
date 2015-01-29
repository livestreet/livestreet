{**
 * Кроп фотографии
 *}

{extends 'Component@crop.crop'}

{block 'modal_options' append}
    {$title = {lang 'user.photo.crop_avatar.title'}}
    {$desc = {lang 'user.photo.crop_avatar.desc'}}
    {$usePreview = true}
{/block}