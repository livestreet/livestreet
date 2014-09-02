{extends './pane.tpl'}

{block 'media_pane_options' append}
	{$id = 'tab-media-photoset'}
{/block}

{block 'media_pane_footer' prepend}
	<button type="submit" class="button button--primary js-media-insert-button js-media-insert-photoset">Создать фотосет</button>
{/block}