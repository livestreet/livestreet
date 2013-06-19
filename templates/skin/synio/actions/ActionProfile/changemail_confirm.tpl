{**
 * Уведомления о смене емэйла
 *}

{extends file='layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
	{$noShowSystemMessage = true}
{/block}

{block name='layout_content'}
	<div class="content-error">
		<p>{$sText}</p>
	</div>
{/block}