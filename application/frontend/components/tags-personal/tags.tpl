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
        {foreach $tagsPersonal as $tag}
            {component 'tags' template='item'
                text=$tag->getText()
                url=$tag->getUrl()
                classes="js-tags-personal-tag"
                mods="personal"}
        {/foreach}

        {* Кнопка "Изменить теги" *}
        <li class="ls-tags-item ls-tags-personal-edit js-tags-personal-edit" {if $isEditable}style="display:none;"{/if}>
            <a href="#" class="ls-link-dotted">
                {component 'icon' icon='edit'}
                {lang 'tags_personal.edit'}
            </a>
        </li>
    {/if}
{/block}