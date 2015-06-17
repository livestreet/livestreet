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
    {component 'media' template='uploader-block.insert.image'}

    {* Опции фотосета *}
    {component 'media' template='uploader-block.photoset'}
{/block}