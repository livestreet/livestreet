{**
 * Базовая форма создания топика
 *
 * @param object $topic
 * @param object $type
 * @param array  $blogs
 * @param array  $blogId
 *}

{component_define_params params=[ 'topic', 'type', 'skipBlogs', 'blogs', 'classes' ]}

<form action="" method="POST" enctype="multipart/form-data" id="topic-add-form" class="{$classes} js-form-validate" data-content-action="{( $topic ) ? 'edit' : 'add'}">
    {hook run="form_add_topic_begin" topic=$topic}
    {block 'add_topic_form_begin'}{/block}

    {* Выбор блога *}
    {if ! $skipBlogs}
        {$blogsSelect = []}
        {$blogsSelectId = []}
        {$blogsSelectedId=[]}

        {foreach $blogs as $blogType => $blogItems}
            {$blogsSelectOptions = []}

            {foreach $blogItems as $blog}
                {$blogsSelectOptions[] = [
                    'text' => $blog->getTitle()|escape,
                    'value' => $blog->getId()
                ]}
                {$blogsSelectId[]=$blog->getId()}
            {/foreach}

            {$blogsSelect[] = [
                'text' => {lang "blog.types.{$blogType}"},
                'value' => $blogsSelectOptions
            ]}
        {/foreach}

        {if $topic}
            {json var=array_intersect($topic->getBlogsId(),$blogsSelectId) assign='chosenOrder'}
            {$blogsSelectedId = $topic->getBlogsId()}
        {else}
            {if $_aRequest.blog_id}
                {$blogsSelectedId[] = $_aRequest.blog_id}
            {/if}
        {/if}

        {component 'field.autocomplete'
            label         = $aLang.topic.add.fields.blog.label
            name          = ''
            placeholder   = $aLang.topic.add.fields.blog.placeholder
            inputClasses  = 'js-topic-add-blogs ls-hidden'
            isMultiple    = true
            selectedValue = $blogsSelectedId
            inputAttributes    = [ 'data-chosen-order' => {$chosenOrder} ]
            items         = $blogsSelect}
    {/if}


    {* Заголовок топика *}
    {component 'field' template='text'
        name        = 'topic[topic_title]'
        value       = {(( $topic ) ? $topic->getTitle() : '')}
        entityField = 'topic_title'
        entity      = 'ModuleTopic_EntityTopic'
        label       = $aLang.topic.add.fields.title.label}

    {* URL топика *}
    {if $oUserCurrent->isAdministrator()}
        {component 'field' template='text'
            name        = 'topic[topic_slug_raw]'
            value       = {(( $topic ) ? $topic->getSlug() : '')}
            note        = {lang 'topic.add.fields.slug.note'}
            label       = {lang 'topic.add.fields.slug.label'}}
    {/if}

    {block 'add_topic_form_text_before'}{/block}


    {* Текст топика *}
    {if $type->getParam('allow_text')}
        {component 'editor'
            name            = 'topic[topic_text_source]'
            value           = (( $topic ) ? $topic->getTextSource() : '')
            label           = $aLang.topic.add.fields.text.label
            entityField     = 'topic_text_source'
            entity          = 'ModuleTopic_EntityTopic'
            inputClasses    = 'js-editor-default'
            mediaTargetType = 'topic'
            mediaTargetId   = ( $topic ) ? $topic->getId() : ''}
    {/if}

    {block 'add_topic_form_text_after'}{/block}


    {* Теги *}
    {if $type->getParam('allow_tags')}
        {$tagsCountMin=Config::Get('module.topic.tags_count_min')}
        {$tagsCountMax=Config::Get('module.topic.tags_count_max')}
        {component 'field' template='text'
            name    = 'topic[topic_tags]'
            value     = {(( $topic ) ? $topic->getTags() : '')}
            rules   = [ 'required' => !Config::Get('module.topic.tags_allow_empty'), 'rangetags' => "[{$tagsCountMin},{$tagsCountMax}]" ]
            label   = {lang 'topic.add.fields.tags.label'}
            note    = {lang 'topic.add.fields.tags.note'}
            inputClasses = 'ls-width-full autocomplete-tags-sep'}
    {/if}


    {* Показывает дополнительные поля *}
    {insert name='block' block='propertyUpdate' params=[
        'target'      => $topic,
        'entity'      => 'ModuleTopic_EntityTopic',
        'target_type' => "topic_{$type->getCode()}"
    ]}


    {* Выбор превью *}
    {if $type->getParam('allow_preview')}
        {component 'field' template='image-ajax'
            label      = 'Превью'
            modalTitle = 'Выбор превью для топика'
            targetType = 'topic'
            targetId   = ( $topic ) ? $topic->getId() : ''
            classes    = 'js-topic-add-field-image-preview'}
    {/if}

    {* Вставка опросов *}
    {if $type->getParam('allow_poll')}
        {component 'poll' template='manage'
            targetType = 'topic'
            targetId   = ( $topic ) ? $topic->getId() : ''}
    {/if}


    {* Запретить комментарии *}
    {component 'field' template='checkbox'
        name    = 'topic[topic_forbid_comment]'
        checked = {( $topic && $topic->getForbidComment() ) ? true : false }
        note    = $aLang.topic.add.fields.forbid_comments.note
        label   = $aLang.topic.add.fields.forbid_comments.label}


    {* Принудительный вывод топиков на главную (доступно только админам) *}
    {if $oUserCurrent->isAdministrator()}
        {component 'field' template='checkbox'
            name    = 'topic[topic_publish_index]'
            checked = {($topic && $topic->getPublishIndex()) ? true : false }
            note    = $aLang.topic.add.fields.publish_index.note
            label   = $aLang.topic.add.fields.publish_index.label}

        {component 'field' template='checkbox'
            name    = 'topic[topic_skip_index]'
            checked = {($topic && $topic->getSkipIndex()) ? true : false }
            note    = $aLang.topic.add.fields.skip_index.note
            label   = $aLang.topic.add.fields.skip_index.label}
    {/if}


    {block 'add_topic_form_end'}{/block}
    {hook run="form_add_topic_end" topic=$topic}


    {* Скрытые поля *}
    {component 'field' template='hidden' name='topic_type' value=$type->getCode()}

    {if $topic}
        {component 'field' template='hidden' name='topic[id]' value=$topic->getId()}
    {/if}


    {**
     * Кнопки
     *}

    {* Опубликовать / Сохранить изменения *}
    {component 'button'
        id      = {( $topic ) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
        mods    = 'primary'
        classes = 'ls-fl-r'
        text    = $aLang.topic.add.button[ ( !$topic or ( $topic && $topic->getPublish() == 0 ) ) ? 'publish' : 'update' ]}

    {* Превью *}
    {component 'button' type='button' classes='js-topic-preview-text-button' text=$aLang.common.preview_text}

    {* Сохранить в черновиках / Перенести в черновики *}
    {if ! $topic}
        {component 'button' type='button' classes='js-topic-draft-button' text=$aLang.topic.add.button.save_as_draft}
    {else}
        {component 'button' type='button' classes='js-topic-draft-button' text=$aLang.topic.add.button[ ( $topic->getPublish() != 0 ) ? 'mark_as_draft' : 'update' ]}
    {/if}
</form>


{* Блок с превью текста *}
{component 'topic' template='preview'}