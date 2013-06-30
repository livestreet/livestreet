{**
 * Уведомления о смене емэйла
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
	{$bNoSystemMessages = true}
{/block}

{block name='layout_content'}
	<div class="content-error">
		<p>{$sText}</p>
	</div>
{/block}