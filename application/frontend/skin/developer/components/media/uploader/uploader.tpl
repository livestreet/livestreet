{extends 'components/uploader/uploader.tpl'}

{block 'uploader_options' append}
	{$attributes = "{$attributes} data-param-target_type={json var=$smarty.local.targetType} data-param-target_id={json var=$smarty.local.targetId} data-param-target_tmp={json var=$smarty.local.targetTmp}"}
{/block}

{block 'uploader_aside' append}
	{* Основные настройки *}
	{include './uploader-block.insert.tpl'}

	{* Опции фотосета *}
	{include './uploader-block.photoset.tpl'}
{/block}