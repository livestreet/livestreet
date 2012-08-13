{assign var="sHookPrefix" value="topic_preview_"}
{assign var="bTopicPreview" value=true}

<h3 class="profile-page-header">{$aLang.topic_preview}</h3>
	
{include file="topic_{$oTopic->getType()}.tpl"}
	
<button type="submit" name="submit_topic_publish" class="button button-primary fl-r"
	onclick="jQuery('#submit_topic_publish').trigger('click');"
	>{$aLang.topic_create_submit_publish}</button>
<button type="submit" name="submit_preview" class="button"
	onclick="jQuery('#text_preview').html('').hide();"
	>{$aLang.topic_create_submit_preview_close}</button>
<button type="submit" name="submit_topic_save" class="button"
	onclick="jQuery('#submit_topic_save').trigger('click');"
	>{$aLang.topic_create_submit_save}</button>