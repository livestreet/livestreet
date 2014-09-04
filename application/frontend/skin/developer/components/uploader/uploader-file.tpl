{**
 * Файл
 *}

{$component = 'uploader-file'}

{$file = $oMediaItem}

<li class="{$component} js-{$component}"
		data-media-id="{$file->getId()}"
		data-media-type="{$file->getType()}"
		data-media-date-add="{date_format date=$file->getDateAdd() format='j F Y, H:i'}"
		data-media-title="{$file->getDataOne('title')|escape}"
		data-media-file-name="{$file->getFileName()|escape}"
		data-media-file-size="{$file->getFileSize()}"
		data-media-width="{$file->getWidth()}"
		data-media-height="{$file->getHeight()}"
		data-media-dimensions="{$file->getWidth()}x{$file->getHeight()}"
		data-media-preview="{$file->getFileWebPath('100crop')}"
		data-media-image-sizes={json var=$file->getDataOne('image_sizes')}
		data-media-relation-is-preview={json var=$file->getRelationTarget()->getIsPreview()}>
	{* Превью *}
	<img src="{$file->getFileWebPath('100crop')}" alt="{$file->getFileName()|escape}" class="{$component}-image">

	{* Название файла *}
	<div class="{$component}-name">
		{$file->getFileName()|escape}
	</div>
</li>