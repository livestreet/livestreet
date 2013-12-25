{**
 * Добавление медиа-файла / Список изображений / Блок с изображением
 *
 * @styles css/modals.css
 *
 * TODO: Передавать сюда объект изображения вместо отдельных переменных
 *}
<li class="js-media-upload-gallery-item modal-upload-image-gallery-list-item {if $bIsSelected}is-selected{/if} {if $bIsActive}active{/if}"
		data-media-id="{$oMediaItem->getId()}"
		data-media-type="{$oMediaItem->getType()}"
		data-media-date-add="{$oMediaItem->getDateAdd()}"
		data-media-data-title="{$oMediaItem->getDataOne('title')|escape:'html'}"
		data-media-file-name="{$oMediaItem->getFileName()|escape:'html'}"
		data-media-file-size="{$oMediaItem->getFileSize()}"
		data-media-width="{$oMediaItem->getWidth()}"
		data-media-height="{$oMediaItem->getHeight()}"
		data-media-preview="{$oMediaItem->getFileWebPath('100crop')}"
		data-media-image-sizes={json var=$oMediaItem->getDataOne('image_sizes')}
		>
	<img src="{$oMediaItem->getFileWebPath('100crop')}" alt="Image">

	<input id="checkbox_02" type="checkbox">
	<label for="checkbox_02" class="modal-upload-image-gallery-list-item-checkbox" title="Убрать выделение"></label>
</li>