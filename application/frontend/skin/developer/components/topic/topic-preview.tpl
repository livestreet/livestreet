{**
 * Предпросмотр топика
 *
 * @param object $topic
 *}

<div class="topic-preview" id="topic-text-preview">
	<header class="topic-preview-header">
		<h3 class="topic-preview-title">{$aLang.common.preview_text}</h3>
	</header>

	<div class="topic-preview-body js-topic-preview-content"></div>

	<footer class="topic-preview-footer">
        {component 'button' type='button' classes='js-topic-preview-text-hide-button' text=$aLang.common.cancel}
	</footer>
</div>