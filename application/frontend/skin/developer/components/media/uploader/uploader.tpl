{extends 'components/uploader/uploader.tpl'}

{block 'uploader_aside' append}
	{* Основные настройки *}
	{include './uploader-block.insert.tpl'}

	{* Опции фотосета *}
	{include './uploader-block.photoset.tpl'}
{/block}