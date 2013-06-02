{**
 * Создание топика-ссылки
 *
 * @styles css/topic.css
 *}

{extends file='forms/form.add.topic.base.tpl'}

{block name='add_topic_form_text_before'}
	<p><label for="topic_link_url">{$aLang.topic_link_create_url}:</label>
	<input type="text" id="topic_link_url" name="topic_link_url" value="{$_aRequest.topic_link_url}" class="width-full" />
	<small class="note">{$aLang.topic_link_create_url_notice}</small></p>
{/block}