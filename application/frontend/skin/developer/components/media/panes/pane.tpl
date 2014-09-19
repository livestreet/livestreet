{block 'media_pane_options'}
	{$id = $smarty.local.id}
{/block}

<div id="{$id}" data-type="tab-pane" class="tab-pane media-pane" {if $smarty.local.isActive}style="display: block;"{/if}>
	<div class="media-pane-content js-media-pane-content">
		{block 'media_pane_content'}{/block}
	</div>

	<div class="media-pane-footer">
		{block 'media_pane_footer'}{/block}
	</div>
</div>