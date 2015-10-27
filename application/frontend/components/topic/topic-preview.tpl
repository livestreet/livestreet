{**
 * Предпросмотр топика
 *
 * @param object $topic
 *}

{$component = 'ls-topic-preview'}

<div class="{$component}" id="topic-text-preview">
    <header class="{$component}-header">
        <h3 class="{$component}-title">{$aLang.common.preview_text}</h3>
    </header>

    <div class="{$component}-body js-topic-preview-content"></div>

    <footer class="{$component}-footer">
        {component 'button' type='button' classes='js-topic-preview-text-hide-button' text=$aLang.common.cancel}
    </footer>
</div>