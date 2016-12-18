{extends 'Component@uploader.uploader'}

{block 'uploader_options' append}
    {component_define_params params=[ 'targetType', 'targetId', 'targetTmp' ]}

    {$attributes = array_merge( $attributes|default:[], [
        'data-param-target_type' => {json var=$targetType},
        'data-param-target_id'   => {json var=$targetId},
        'data-param-target_tmp'  => {json var=$targetTmp}
    ])}
{/block}

{block 'uploader_aside' append}
    {* Основные настройки *}
    {component 'media' template='uploader-block.insert.image'}

    {* Опции фотосета *}
    {component 'media' template='uploader-block.photoset'}
{/block}