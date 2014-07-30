{**
 * Предпросмотр топика
 *
 * @param object $topic
 *
 * @styles css/topic.css
 *}

<div class="topic-preview">
	<header class="topic-preview-header">
		<h3 class="topic-preview-title">{$aLang.common.preview_text}</h3>
	</header>

	<div class="topic-preview-body">
		{include './topic-type.tpl' topic=$smarty.local.topic isPreview=true}
	</div>

	<footer class="topic-preview-footer">
		<button type="button" name="submit_preview" class="button js-topic-preview-text-hide-button">{$aLang.common.cancel}</button>
	</footer>
</div>