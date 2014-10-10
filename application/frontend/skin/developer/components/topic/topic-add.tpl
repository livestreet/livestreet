{**
 * Базовая форма создания топика
 *
 * @param object $topic
 * @param object $type
 * @param array  $blogs
 *}

{$topic = $smarty.local.topic}
{$type = $smarty.local.type}

{block name='add_topic_options'}{/block}

{hook run="add_topic_begin"}
{block name='add_topic_header_after'}{/block}


<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="js-form-validate" onsubmit="return false;">
	{hook run="form_add_topic_begin"}
	{block name='add_topic_form_begin'}{/block}


	{* Выбор блога *}
	{$items = [[
		'value' => 0,
		'text' => $aLang.topic.add.fields.blog.option_personal
	]]}

	{foreach $smarty.local.blogs as $blog}
		{$items[] = [
			'value' => $blog->getId(),
			'text' => $blog->getTitle()
		]}
	{/foreach}

	{include 'components/field/field.select.tpl'
		sName          = 'topic[blog_id]'
		sLabel         = $aLang.topic.add.fields.blog.label
		sNote          = $aLang.topic.add.fields.blog.note
		sInputClasses  = 'js-topic-add-title'
		aItems         = $items
		sSelectedValue = {( $topic ) ? $topic->getBlogId() : '' }}


	{* Заголовок топика *}
	{include 'components/field/field.text.tpl'
		sName        = 'topic[topic_title]'
		sValue       = {(( $topic ) ? $topic->getTitle() : '')|escape:'html' }
		sEntityField = 'topic_title'
		sEntity      = 'ModuleTopic_EntityTopic'
		sLabel       = $aLang.topic.add.fields.title.label}

	{block name='add_topic_form_text_before'}{/block}



	{* Текст топика *}
	{if $type->getParam('allow_text')}
		{include 'components/editor/editor.tpl'
			sName            = 'topic[topic_text_source]'
			sValue           = (( $topic ) ? $topic->getTextSource() : '')|escape
			sLabel           = $aLang.topic.add.fields.text.label
			sEntityField     = 'topic_text_source'
			sEntity          = 'ModuleTopic_EntityTopic'
			classes          = 'js-editor-default'
			sMediaTargetType = 'topic'
			sMediaTargetId   = ( $topic ) ? $topic->getId() : ''}
	{/if}

	{block name='add_topic_form_text_after'}{/block}


	{* Теги *}
	{if $type->getParam('allow_tags')}
		{include 'components/field/field.text.tpl'
			sName    = 'topic[topic_tags]'
			sValue	  = {(( $topic ) ? $topic->getTags() : '')|escape:'html' }
			aRules   = [ 'required' => true, 'rangetags' => '[1,15]' ]
			sLabel   = $aLang.topic.add.fields.tags.label
			sNote    = $aLang.topic.add.fields.tags.note
			sClasses = 'width-full autocomplete-tags-sep'}
	{/if}


	{* Показывает дополнительные поля *}
	{insert name="block" block="propertyUpdate" params=[ 'target' => $topic, 'entity' => 'ModuleTopic_EntityTopic', 'target_type' => 'topic_'|cat:$type->getCode() ]}



	{* Вставка опросов *}
	{if $type->getParam('allow_poll')}
		{include 'components/poll/poll.manage.tpl'
			sTargetType = 'topic'
			sTargetId   = ( $topic ) ? $topic->getId() : ''}
	{/if}


	{* Запретить комментарии *}
	{include 'components/field/field.checkbox.tpl'
		sName    = 'topic[topic_forbid_comment]'
		bChecked = {( $topic && $topic->getForbidComment() ) ? true : false }
		sNote    = $aLang.topic.add.fields.forbid_comments.note
		sLabel   = $aLang.topic.add.fields.forbid_comments.label}


	{* Принудительный вывод топиков на главную (доступно только админам) *}
	{if $oUserCurrent->isAdministrator()}
		{include 'components/field/field.checkbox.tpl'
			sName    = 'topic[topic_publish_index]'
			bChecked = {($topic && $topic->getPublishIndex()) ? true : false }
			sNote    = $aLang.topic.add.fields.publish_index.note
			sLabel   = $aLang.topic.add.fields.publish_index.label}
	{/if}


	{block name='add_topic_form_end'}{/block}
	{hook run="form_add_topic_end"}


	{* Скрытые поля *}
	{include 'components/field/field.hidden.tpl' sName='topic_type' sValue=$type->getCode()}

	{if $topic}
		{include "components/field/field.hidden.tpl" sName='topic[id]' sValue=$topic->getId()}
	{/if}


	{**
	 * Кнопки
	 *}

	{* Опубликовать / Сохранить изменения *}
	{include 'components/button/button.tpl'
		id      = {( $topic ) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
		mods    = 'primary'
		classes = 'fl-r'
		text    = $aLang.topic.add.button[ ( $sEvent == 'add' or ( $topic and $topic->getPublish() == 0 ) ) ? 'publish' : 'update' ]}

	{* Превью *}
	{include 'components/button/button.tpl' type='button' classes='js-topic-preview-text-button' text=$aLang.common.preview_text}

	{* Сохранить в черновиках / Перенести в черновики *}
	{if $topic && $topic->getPublish() != 0}
		{include 'components/button/button.tpl'
			id   = {( $topic ) ? 'submit-edit-topic-save' : 'submit-add-topic-save' }
			text = $aLang.topic.add.button[ ( $sEvent == 'add' ) ? 'save_as_draft' : 'mark_as_draft' ]}
	{/if}
</form>


{* Блок с превью текста *}
<div style="display: none;" id="topic-text-preview"></div>

{block name='add_topic_end'}{/block}
{hook run="add_topic_end"}