{**
 * Обрезка загруженного изображения
 *
 * @param string $title
 * @param string $desc
 * @param string $image
 * @param integer $width
 * @param integer $height
 * @param integer $originalWidth
 * @param integer $originalHeight
 *
 * TODO: Возможность задавать размеры превью
 * TODO: Разделить на модальное и на контейнер с кропом
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$mods = "$mods crop"}
    {$title = $smarty.local.title|escape|default:{lang 'crop.title'}}
    {$desc = $smarty.local.desc|escape}
    {$usePreview = $smarty.local.usePreview}
{/block}

{block 'modal_content'}
    {if $desc}
        <p class="crop-desc">{$desc}</p>
    {/if}

    {$image = "{$smarty.local.image|escape}?v{rand( 0, 10e10 )}"}

    <div class="crop js-crop" data-crop-width="{$smarty.local.originalWidth}" data-crop-height="{$smarty.local.originalHeight}">
        {* Изображение *}
        <div class="crop-image-holder js-crop-image-holder">
            <img src="{$image}" width="{$smarty.local.width}" height="{$smarty.local.height}" class="crop-image js-crop-image">
        </div>

        {* Превью *}
        {if $usePreview}
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
    {component 'button' text=$aLang.common.save classes='js-crop-submit' mods='primary'}
{/block}