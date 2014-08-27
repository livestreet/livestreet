{block 'media_pane_options'}
	{$id = $smarty.local.id}
{/block}

<div id="{$id}" data-type="tab-pane" class="tab-pane modal-upload-image-pane" {if $smarty.local.isActive}style="display: block;"{/if}>
	<div class="modal-content">
		{block 'media_pane_content'}{/block}
	</div>

	<div class="modal-footer">
		{block 'media_pane_footer'}{/block}
	</div>
</div>