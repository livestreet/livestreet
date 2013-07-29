{**
 * Создание топика-ссылки
 *
 * @styles css/topic.css
 *}

{extends file='forms/form.add.topic.base.tpl'}

{block name='add_topic_form_text_before'}
	{include file='forms/form.field.text.tpl' 
			 sFieldName  = 'topic_link_url' 
			 sFieldRules = 'required="true" type="url"'
			 sFieldNote  = $aLang.topic_link_create_url_notice 
			 sFieldLabel = $aLang.topic_link_create_url}
{/block}