{**
 * Базовая форма создания топика
 *
 * @param object $topic
 * @param object $type
 * @param array  $blogs
 * @param array  $blogId
 *}

{$topic = $smarty.local.topic}
{$type = $smarty.local.type}

{block 'add_topic_options'}{/block}

{hook run="add_topic_begin"}
{block 'add_topic_header_after'}{/block}


<form action="" method="POST" enctype="multipart/form-data" id="topic-add-form" class="js-form-validate" data-content-action="{( $topic ) ? 'edit' : 'add'}">
    {hook run="form_add_topic_begin"}
    {block 'add_topic_form_begin'}{/block}


    {* Выбор блога *}
    {if !$smarty.local.skipBlogs}
        <script type="text/javascript">
            jQuery(function($){
                $(".chosen-select").chosen({ max_selected_options: {Config::Get('module.topic.max_blog_count')} });
            });
        </script>

        {$blogsId=(( $topic ) ? $topic->getBlogsId() : [])}

        {$aLang.topic.add.fields.blog.label}
        <select name="topic[blogs_id_raw][]" data-placeholder="Выберите блог для публикации" style="width:100%;" class="chosen-select" multiple>
            <option value=""></option>
            {foreach $smarty.local.blogs as $blogType => $blogs}
                <optgroup label="{lang "blog.types.{$blogType}"}">
                    {foreach $blogs as $blog}
                        <option {if in_array($blog->getId(),$blogsId)}selected="selected"{/if} value="{$blog->getId()}">{$blog->getTitle()|escape}</option>
                    {/foreach}
                </optgroup>
            {/foreach}
        </select>
    {/if}


    {* Заголовок топика *}
    {include 'components/field/field.text.tpl'
        name        = 'topic[topic_title]'
        value       = {(( $topic ) ? $topic->getTitle() : '')|escape}
        entityField = 'topic_title'
        entity      = 'ModuleTopic_EntityTopic'
        label       = $aLang.topic.add.fields.title.label}

    {block 'add_topic_form_text_before'}{/block}



    {* Текст топика *}
    {if $type->getParam('allow_text')}
        {include 'components/editor/editor.tpl'
            name            = 'topic[topic_text_source]'
            value           = (( $topic ) ? $topic->getTextSource() : '')|escape
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
        {include 'components/field/field.text.tpl'
            name    = 'topic[topic_tags]'
            value     = {(( $topic ) ? $topic->getTags() : '')|escape}
            rules   = [ 'required' => true, 'rangetags' => '[1,15]' ]
            label   = {lang 'topic.add.fields.tags.label'}
            note    = {lang 'topic.add.fields.tags.note'}
            inputClasses = 'width-full autocomplete-tags-sep'}
    {/if}


    {* Показывает дополнительные поля *}
    {insert name='block' block='propertyUpdate' params=[
        'target'      => $topic,
        'entity'      => 'ModuleTopic_EntityTopic',
        'target_type' => "topic_{$type->getCode()}"
    ]}


    {* Выбор превью *}
    {include 'components/field/field.image-ajax.tpl'
        label      = 'Превью'
        modalTitle = 'Выбор превью для топика'
        targetType = 'topic'
        targetId   = ( $topic ) ? $topic->getId() : ''
        classes    = 'js-topic-add-field-image-preview'}


    {* Вставка опросов *}
    {if $type->getParam('allow_poll')}
        {include 'components/poll/poll.manage.tpl'
            targetType = 'topic'
            targetId   = ( $topic ) ? $topic->getId() : ''}
    {/if}


    {* Запретить комментарии *}
    {include 'components/field/field.checkbox.tpl'
        name    = 'topic[topic_forbid_comment]'
        checked = {( $topic && $topic->getForbidComment() ) ? true : false }
        note    = $aLang.topic.add.fields.forbid_comments.note
        label   = $aLang.topic.add.fields.forbid_comments.label}


    {* Принудительный вывод топиков на главную (доступно только админам) *}
    {if $oUserCurrent->isAdministrator()}
        {include 'components/field/field.checkbox.tpl'
            name    = 'topic[topic_publish_index]'
            checked = {($topic && $topic->getPublishIndex()) ? true : false }
            note    = $aLang.topic.add.fields.publish_index.note
            label   = $aLang.topic.add.fields.publish_index.label}

        {include 'components/field/field.checkbox.tpl'
            name    = 'topic[topic_skip_index]'
            checked = {($topic && $topic->getSkipIndex()) ? true : false }
            note    = $aLang.topic.add.fields.skip_index.note
            label   = $aLang.topic.add.fields.skip_index.label}
    {/if}


    {block 'add_topic_form_end'}{/block}
    {hook run="form_add_topic_end"}


    {* Скрытые поля *}
    {include 'components/field/field.hidden.tpl' name='topic_type' value=$type->getCode()}

    {if $topic}
        {include "components/field/field.hidden.tpl" name='topic[id]' value=$topic->getId()}
    {/if}


    {**
     * Кнопки
     *}

    {* Опубликовать / Сохранить изменения *}
    {include 'components/button/button.tpl'
        id      = {( $topic ) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
        mods    = 'primary'
        classes = 'fl-r'
        text    = $aLang.topic.add.button[ ( $sEvent == 'add' or ( $topic && $topic->getPublish() == 0 ) ) ? 'publish' : 'update' ]}

    {* Превью *}
    {include 'components/button/button.tpl' type='button' classes='js-topic-preview-text-button' text=$aLang.common.preview_text}

    {* Сохранить в черновиках / Перенести в черновики *}
    {if ! $topic || ( $topic && $topic->getPublish() != 0 )}
        {include 'components/button/button.tpl' type='button' classes='js-topic-draft-button' text=$aLang.topic.add.button[ ( $sEvent == 'add' ) ? 'save_as_draft' : 'mark_as_draft' ]}
    {/if}
</form>


{* Блок с превью текста *}
{include './topic-preview.tpl'}

{block 'add_topic_end'}{/block}
{hook run="add_topic_end"}