{**
 * Ресайз загруженного изображения
 *
 * @styles css/modals.css
 *
 * TODO: Унифицировать
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-image-crop{/block}
{block 'modal_class'}modal-image-crop js-modal-default{/block}
{block 'modal_title'}{$aLang.uploadimg}{/block}

{block 'modal_content'}
	<div class="crop">
		<div class="crop-image-holder js-crop-image-holder">
			<img src="{$sImageSrc|escape}" style="width: 370px;" class="crop-image js-crop-image">
		</div>

		<div class="crop-previews js-crop-previews">
			{foreach [ 100, 64, 48 ] as $size}
				<div style="width: {$size}px; height: {$size}px;" class="crop-preview js-crop-preview">
					<img src="{$sImageSrc|escape}" class="js-crop-preview-image" data-size="{$size}">
				</div>
			{/foreach}
		</div>
	</div>
{/block}

{block 'modal_footer_begin'}
	{include 'components/button/button.tpl' sText=$aLang.common.save sClasses='js-ajax-image-crop-submit' sMods='primary'}
{/block}