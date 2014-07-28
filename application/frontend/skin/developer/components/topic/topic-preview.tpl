{**
 * Предпросмотр топика
 *
 * @styles css/topic.css
 *}

{$oUser = $oTopic->getUser()}

<h3 class="profile-page-header">{$aLang.common.preview_text}</h3>

{include './topic-type.tpl' topic=$oTopic}

{* TODO: Пофиксить кнопки сабмита *}
<button type="submit" name="submit_topic_publish" class="button button-primary fl-r" onclick="jQuery('#submit_topic_publish').trigger('click');">
	{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}
		{$aLang.topic_create_submit_publish}
	{else}
		{$aLang.topic_create_submit_update}
	{/if}
</button>
<button type="button" name="submit_preview" class="button js-topic-preview-text-hide-button">{$aLang.common.cancel}</button>
<button type="submit" name="submit_topic_save" class="button" onclick="jQuery('#submit_topic_save').trigger('click');">{$aLang.topic_create_submit_save}</button>