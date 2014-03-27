{**
 * Базовая форма создания топика
 *
 * @styles css/topic.css
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{if $sEvent == 'add'}
		{$sNav = 'create'}
	{/if}
{/block}

{block name='layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.topic_create}
	{else}
		{$aLang.topic_topic_edit}
	{/if}
{/block}

{block name='layout_content'}
	{block name='add_topic_options'}{/block}

	{* Подключение редактора *}
	{$sMediaTargetId=''}
	{if $oTopicEdit}
		{$sMediaTargetId=$oTopicEdit->getId()}
	{/if}
	{include file='forms/editor.init.tpl' sMediaTargetType='topic' sMediaTargetId=$sMediaTargetId}


	{hook run="add_topic_begin"}
	{block name='add_topic_header_after'}{/block}


	<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="js-form-validate" onsubmit="return false;">
		{hook run="form_add_topic_begin"}
		{block name='add_topic_form_begin'}{/block}


		{* Выбор блога *}
		{$aBlogs[] = [
			'value' => 0,
			'text' => $aLang.topic_create_blog_personal
		]}

		{foreach $aBlogsAllow as $oBlog}
			{$aBlogs[] = [
				'value' => $oBlog->getId(),
				'text' => $oBlog->getTitle()
			]}
		{/foreach}

		{include file='forms/fields/form.field.select.tpl'
			sFieldName          = 'topic[blog_id]'
			sFieldLabel         = $aLang.topic_create_blog
			sFieldNote          = $aLang.topic_create_blog_notice
			sFieldClasses       = 'width-full js-topic-add-title'
			aFieldItems         = $aBlogs
			sFieldSelectedValue = {($oTopicEdit) ? $oTopicEdit->getBlogId() : '' }}


		{* Заголовок топика *}
		{include file='forms/fields/form.field.text.tpl'
			sFieldName				= 'topic[topic_title]'
			sFieldValue				= {(($oTopicEdit) ? $oTopicEdit->getTitle() : '')|escape:'html' }
			sFieldEntityField		= 'topic_title'
			sFieldEntity			= 'ModuleTopic_EntityTopic'
			sFieldNote				= $aLang.topic_create_title_notice
			sFieldLabel			= $aLang.topic_create_title}


		{block name='add_topic_form_text_before'}{/block}



		{* Текст топика *}
		{if $oTopicType->getParam('allow_text')}
			{include file='forms/fields/form.field.textarea.tpl'
				sFieldName    = 'topic[topic_text_source]'
				sFieldValue	  = {(($oTopicEdit) ? $oTopicEdit->getTextSource() : '')|escape:'html' }
				sFieldRules   = 'required="true" rangelength="[2,'|cat:$oConfig->Get('module.topic.max_length')|cat:']"'
				sFieldLabel   = $aLang.topic_create_text
				sFieldClasses = 'width-full js-editor'}

			{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
			{if ! $oConfig->GetValue('view.wysiwyg')}
				{include file='forms/editor.help.tpl' sTagsTargetId='topic_text'}
			{/if}
		{/if}


		{block name='add_topic_form_text_after'}{/block}


		{* Теги *}
		{if $oTopicType->getParam('allow_tags')}
			{include file='forms/fields/form.field.text.tpl'
				sFieldName    = 'topic[topic_tags]'
				sFieldValue	  = {(($oTopicEdit) ? $oTopicEdit->getTags() : '')|escape:'html' }
				sFieldRules   = 'required="true" rangetags="[1,15]"'
				sFieldNote    = $aLang.topic_create_tags_notice
				sFieldLabel   = $aLang.topic_create_tags
				sFieldClasses = 'width-full autocomplete-tags-sep'}
		{/if}

		{* Показывает дополнительные поля *}
		{$aBlockParams = []}
		{$aBlockParams.target_type = 'topic_'|cat:$oTopicType->getCode()}
		{if $oTopicEdit}
			{$aBlockParams.target_id = $oTopicEdit->getId()}
		{/if}

		{insert name="block" block="propertyUpdate" params=$aBlockParams}

		{* Вставка опросов *}
		{if $oTopicType->getParam('allow_poll')}
			{include file='polls/poll.form.inject.tpl'
				sTargetType  = 'topic'
				sTargetId = {($oTopicEdit) ? $oTopicEdit->getId() : '' }
			}
		{/if}

		{* Запретить комментарии *}
		{include file='forms/fields/form.field.checkbox.tpl'
			sFieldName  = 'topic[topic_forbid_comment]'
			bFieldChecked = {($oTopicEdit && $oTopicEdit->getForbidComment()) ? true : false }
			sFieldNote  = $aLang.topic_create_forbid_comment_notice
			sFieldLabel = $aLang.topic_create_forbid_comment}


		{* Принудительный вывод топиков на главную (доступно только админам) *}
		{if $oUserCurrent->isAdministrator()}
			{include file='forms/fields/form.field.checkbox.tpl'
				sFieldName  = 'topic[topic_publish_index]'
				bFieldChecked = {($oTopicEdit && $oTopicEdit->getPublishIndex()) ? true : false }
				sFieldNote  = $aLang.topic_create_publish_index_notice
				sFieldLabel = $aLang.topic_create_publish_index}
		{/if}


		{block name='add_topic_form_end'}{/block}
		{hook run="form_add_topic_end"}


		{* Скрытые поля *}
		{include file='forms/fields/form.field.hidden.tpl' sFieldName='topic_type' sFieldValue=$oTopicType->getCode()}


		{* Кнопки *}
		{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}
			{$sSubmitInputText = $aLang.topic_create_submit_publish}
		{else}
			{$sSubmitInputText = $aLang.topic_create_submit_update}
		{/if}

		{if $oTopicEdit}
			{include file="forms/fields/form.field.hidden.tpl" sFieldName='topic[id]' sFieldValue=$oTopicEdit->getId()}
		{/if}

		{include file='forms/fields/form.field.button.tpl'
			sFieldId    = {($oTopicEdit) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
			sFieldStyle   = 'primary'
			sFieldClasses = 'fl-r'
			sFieldText    = $sSubmitInputText}
		{include file='forms/fields/form.field.button.tpl' sFieldType='button' sFieldClasses='js-topic-preview-text-button' sFieldText=$aLang.topic_create_submit_preview}
		{include file='forms/fields/form.field.button.tpl' sFieldId={($oTopicEdit) ? 'submit-edit-topic-save' : 'submit-add-topic-save' } sFieldText=$aLang.topic_create_submit_save}
</form>


{* Блок с превью текста *}
<div class="topic-preview" style="display: none;" id="topic-text-preview"></div>


	{block name='add_topic_end'}{/block}
	{hook run="add_topic_end"}
{/block}