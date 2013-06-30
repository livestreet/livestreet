{**
 * Страница вывода ошибок
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
	{$bNoSystemMessages = true}
{/block}

{block name='layout_content'}
	<div class="content-error">
		{if $aMsgError[0].title}
			<h2 class="page-header">{$aLang.error}: <span>{$aMsgError[0].title}</span></h2>
		{/if}

		<p>{$aMsgError[0].msg}</p>
		<br />
		<br />
		<p><a href="javascript:history.go(-1);">{$aLang.site_history_back}</a><br />
		<a href="{cfg name='path.root.web'}">{$aLang.site_go_main}</a></p>
	</div>
{/block}