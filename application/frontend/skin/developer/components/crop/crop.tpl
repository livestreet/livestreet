{**
 * Ресайз загруженного изображения
 *
 * @param string $image
 * @param array  $width
 * @param array  $height
 *
 * TODO: Возможность задавать размеры превью
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-image-crop{/block}
{block 'modal_class'}modal--crop js-modal-default{/block}
{block 'modal_title'}{lang 'modal_image_crop.title'}{/block}

{block 'modal_content'}
    {$image = "{$smarty.local.image|escape}?v{rand( 0, 10e10 )}"}

    <div class="crop js-crop" data-crop-width="{$smarty.local.width}" data-crop-height="{$smarty.local.height}">
        {* Изображение *}
        <div class="crop-image-holder js-crop-image-holder">
            <img src="{$image}" style="width: 370px;" class="crop-image js-crop-image">
        </div>

        {* Превью *}
        {if $smarty.local.usePreview}
            <div class="crop-previews js-crop-previews">
                {foreach [ 100, 64, 48 ] as $size}
                    <div style="width: {$size}px; height: {$size}px;" class="crop-preview js-crop-preview">
                        <img src="{$image}" class="js-crop-preview-image" data-size="{$size}">
                    </div>
                {/foreach}
            </div>
        {/if}
    </div>
{/block}

{block 'modal_footer_begin'}
    {include 'components/button/button.tpl' text=$aLang.common.save classes='js-crop-submit' mods='primary'}
{/block}