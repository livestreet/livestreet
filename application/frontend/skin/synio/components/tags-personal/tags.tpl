{**
 * Список тегов
 *}

{extends 'component@tags.tags'}

{block 'tags_options' append}
    {component_define_params params=[ 'targetId', 'tagsPersonal', 'isEditable' ]}

    {$attributes = array_merge( $attributes|default:[], [
        'data-param-target_id' => $targetId
    ])}
{/block}

{block 'tags_list' append}
    {* Персональные теги *}
    {if $oUserCurrent}
        <span class="ls-tags-personal js-tags-personal-tags">
            {foreach $tagsPersonal as $tag}
                {component 'tags' template='item'
                    text=$tag->getText()
                    url=$tag->getUrl()
                    classes='js-tags-personal-tag'
                    mods="personal"
                    isLast=$tag@last}
            {/foreach}
        </span>

        {* Кнопка "Изменить теги" *}
        <a href="#" class="ls-tags-item ls-tags-personal-edit js-tags-personal-edit" {if $isEditable}style="display:none;"{/if}>
            {lang 'tags_personal.edit'}
        </a>
    {/if}
{/block}