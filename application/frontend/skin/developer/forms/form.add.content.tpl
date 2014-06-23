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

		{include file='components/field/field.select.tpl'
			sName          = 'topic[blog_id]'
			sLabel         = $aLang.topic_create_blog
			sNote          = $aLang.topic_create_blog_notice
			sInputClasses  = 'js-topic-add-title'
			aItems         = $aBlogs
			sSelectedValue = {($oTopicEdit) ? $oTopicEdit->getBlogId() : '' }}


		{* Заголовок топика *}
		{include file='components/field/field.text.tpl'
			sName				= 'topic[topic_title]'
			sValue				= {(($oTopicEdit) ? $oTopicEdit->getTitle() : '')|escape:'html' }
			sEntityField		= 'topic_title'
			sEntity			= 'ModuleTopic_EntityTopic'
			sNote				= $aLang.topic_create_title_notice
			sLabel			= $aLang.topic_create_title}


		{block name='add_topic_form_text_before'}{/block}



		{* Текст топика *}
		{if $oTopicType->getParam('allow_text')}
			{include 'components/editor/editor.tpl'
					sName            = 'topic[topic_text_source]'
					sValue           = (($oTopicEdit) ? $oTopicEdit->getTextSource() : '')|escape
					sLabel           = $aLang.topic_create_text
					sEntityField	 = 'topic_text_source'
					sEntity			 = 'ModuleTopic_EntityTopic'
					sMediaTargetType = 'topic'
					sMediaTargetId   = ($oTopicEdit) ? $oTopicEdit->getId() : ''}
		{/if}


		{block name='add_topic_form_text_after'}{/block}


		{* Теги *}
		{if $oTopicType->getParam('allow_tags')}
			{include file='components/field/field.text.tpl'
				sName    = 'topic[topic_tags]'
				sValue	  = {(($oTopicEdit) ? $oTopicEdit->getTags() : '')|escape:'html' }
				aRules   = [ 'required' => true, 'rangetags' => '[1,15]' ]
				sNote    = $aLang.topic_create_tags_notice
				sLabel   = $aLang.topic_create_tags
				sClasses = 'width-full autocomplete-tags-sep'}
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
			{include file='components/poll/poll.manage.tpl'
				sTargetType  = 'topic'
				sTargetId = ($oTopicEdit) ? $oTopicEdit->getId() : ''}
		{/if}

		{* Запретить комментарии *}
		{include file='components/field/field.checkbox.tpl'
			sName  = 'topic[topic_forbid_comment]'
			bChecked = {($oTopicEdit && $oTopicEdit->getForbidComment()) ? true : false }
			sNote  = $aLang.topic_create_forbid_comment_notice
			sLabel = $aLang.topic_create_forbid_comment}


		{* Принудительный вывод топиков на главную (доступно только админам) *}
		{if $oUserCurrent->isAdministrator()}
			{include file='components/field/field.checkbox.tpl'
				sName  = 'topic[topic_publish_index]'
				bChecked = {($oTopicEdit && $oTopicEdit->getPublishIndex()) ? true : false }
				sNote  = $aLang.topic_create_publish_index_notice
				sLabel = $aLang.topic_create_publish_index}
		{/if}


		{block name='add_topic_form_end'}{/block}
		{hook run="form_add_topic_end"}


		{* Скрытые поля *}
		{include file='components/field/field.hidden.tpl' sName='topic_type' sValue=$oTopicType->getCode()}


		{* Кнопки *}
		{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}
			{$sSubmitInputText = $aLang.topic_create_submit_publish}
		{else}
			{$sSubmitInputText = $aLang.topic_create_submit_update}
		{/if}

		{if $oTopicEdit}
			{include file="components/field/field.hidden.tpl" sName='topic[id]' sValue=$oTopicEdit->getId()}
		{/if}

		{include file='components/button/button.tpl'
			sId    = {($oTopicEdit) ? 'submit-edit-topic-publish' : 'submit-add-topic-publish' }
			sMods   = 'primary'
			sClasses = 'fl-r'
			sText    = $sSubmitInputText}
		{include file='components/button/button.tpl' sType='button' sClasses='js-topic-preview-text-button' sText=$aLang.topic_create_submit_preview}
		{include file='components/button/button.tpl' sId={($oTopicEdit) ? 'submit-edit-topic-save' : 'submit-add-topic-save' } sText=$aLang.topic_create_submit_save}
	</form>


	{* Блок с превью текста *}
	<div class="topic-preview" style="display: none;" id="topic-text-preview"></div>

	{block name='add_topic_end'}{/block}
	{hook run="add_topic_end"}
{/block}