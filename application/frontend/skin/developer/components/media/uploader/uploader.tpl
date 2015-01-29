{extends 'Component@uploader.uploader'}

{block 'uploader_options' append}
    {$attributes = array_merge( $attributes|default:[], [
        'data-param-target_type' => {json var=$smarty.local.targetType},
        'data-param-target_id'   => {json var=$smarty.local.targetId},
        'data-param-target_tmp'  => {json var=$smarty.local.targetTmp}
    ])}
{/block}

{block 'uploader_aside' append}
	{* Основные настройки *}
	{include './uploader-block.insert.image.tpl'}

	{* Опции фотосета *}
	{include './uploader-block.photoset.tpl'}
{/block}