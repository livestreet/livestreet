{**
 * Тулбар
 * Кнопка прокручивания к следующему/предыдущему топику
 *}

{extends 'component@toolbar.toolbar.item'}

{block 'toolbar_item_options' append}
	{$_mods = 'topic'}
	{$_classes = 'js-toolbar-topics'}
	{$_bShow = $params.show}
{/block}

{block 'toolbar_item'}
	{toolbar_item_icon classes='toolbar-topic-prev js-toolbar-topics-prev' title="{lang 'toolbar.topic_nav.prev'}" icon="icon-arrow-up"}
	{toolbar_item_icon classes='toolbar-topic-next js-toolbar-topics-next' title="{lang 'toolbar.topic_nav.next'}" icon="icon-arrow-down"}
{/block}