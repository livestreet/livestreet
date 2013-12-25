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


	{hook run="add_topic_`$sTopicType`_begin"}
	{block name='add_topic_header_after'}{/block}


	<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="js-form-validate">
		{hook run="form_add_topic_`$sTopicType`_begin"}
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

		{include file='forms/form.field.select.tpl'
				 sFieldName          = 'blog_id'
				 sFieldLabel         = $aLang.topic_create_blog
				 sFieldNote          = $aLang.topic_create_blog_notice
				 sFieldClasses       = 'width-full js-topic-add-title'
				 aFieldItems         = $aBlogs
				 sFieldSelectedValue = $_aRequest.blog_id}


		{* Заголовок топика *}
		{include file='forms/form.field.text.tpl'
				 sFieldName				= 'topic_title'
				 sFieldEntity			= 'ModuleTopic_EntityTopic'
				 sFieldEntityScenario	= 'topic'
				 sFieldNote				= $aLang.topic_create_title_notice
				 sFieldLabel			= $aLang.topic_create_title}


		{block name='add_topic_form_text_before'}{/block}


		{* Текст топика *}
		{* TODO: Max length for poll and link *}
		{include file='forms/form.field.textarea.tpl'
				 sFieldName    = 'topic_text'
				 sFieldRules   = 'required="true" rangelength="[2,'|cat:$oConfig->Get('module.topic.max_length')|cat:']"'
				 sFieldLabel   = $aLang.topic_create_text
				 sFieldClasses = 'width-full js-editor'}

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include file='forms/editor.help.tpl' sTagsTargetId='topic_text'}
		{/if}


		{block name='add_topic_form_text_after'}{/block}


		{* Теги *}
		{include file='forms/form.field.text.tpl'
				 sFieldName    = 'topic_tags'
				 sFieldRules   = 'required="true" rangetags="[1,15]"'
				 sFieldNote    = $aLang.topic_create_tags_notice
				 sFieldLabel   = $aLang.topic_create_tags
				 sFieldClasses = 'width-full autocomplete-tags-sep'}


		{* Запретить комментарии *}
		{include file='forms/form.field.checkbox.tpl'
				 sFieldName  = 'topic_forbid_comment'
				 sFieldNote  = $aLang.topic_create_forbid_comment_notice
				 sFieldLabel = $aLang.topic_create_forbid_comment}


		{* Принудительный вывод топиков на главную (доступно только админам) *}
		{if $oUserCurrent->isAdministrator()}
			{include file='forms/form.field.checkbox.tpl'
					 sFieldName  = 'topic_publish_index'
					 sFieldNote  = $aLang.topic_create_publish_index_notice
					 sFieldLabel = $aLang.topic_create_publish_index}
		{/if}


		{block name='add_topic_form_end'}{/block}
		{hook run="form_add_topic_`$sTopicType`_end"}


		{* Скрытые поля *}
		{include file='forms/form.field.hidden.tpl' sFieldName='topic_type' sFieldValue=$sTopicType}
		{include file='forms/form.field.hidden.security_key.tpl'}


		{* Кнопки *}
		{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}
			{$sSubmitInputText = $aLang.topic_create_submit_publish}
		{else}
			{$sSubmitInputText = $aLang.topic_create_submit_update}
		{/if}

		{include file='forms/form.field.button.tpl'
				 sFieldName    = 'submit_topic_publish'
				 sFieldStyle   = 'primary'
				 sFieldClasses = 'fl-r'
				 sFieldText    = $sSubmitInputText}
		{include file='forms/form.field.button.tpl' sFieldType='button' sFieldClasses='js-topic-preview-text-button' sFieldText=$aLang.topic_create_submit_preview}
		{include file='forms/form.field.button.tpl' sFieldName='submit_topic_save' sFieldText=$aLang.topic_create_submit_save}
	</form>


	{* Блок с превью текста *}
	<div class="topic-preview" style="display: none;" id="topic-text-preview"></div>


	{block name='add_topic_end'}{/block}
	{hook run="add_topic_`$sTopicType`_end"}
{/block}