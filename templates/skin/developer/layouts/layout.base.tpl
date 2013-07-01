<!doctype html>

{block name='layout_options'}{/block}

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="ru"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="ru"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="ru"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ru"> <!--<![endif]-->

<head>
	{hook run='html_head_begin'}
	{block name='layout_head_begin'}{/block}

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<meta name="description" content="{block name='layout_description'}{$sHtmlDescription}{/block}">
	<meta name="keywords" content="{block name='layout_keywords'}{$sHtmlKeywords}{/block}">

	<title>{block name='layout_title'}{$sHtmlTitle}{/block}</title>

	{**
	 * Стили
	 * CSS файлы подключаются в конфиге шаблона (ваш_шаблон/settings/config.php)
	 *}
	{$aHtmlHeadFiles.css}

	<link href="{cfg name='path.static.assets'}/images/favicon.ico?v1" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{cfg name='view.name'}" />

	{**
	 * RSS
	 *}
	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}

	{if $sHtmlCanonical}
		<link rel="canonical" href="{$sHtmlCanonical}" />
	{/if}


	<script>
		var DIR_WEB_ROOT 			= '{cfg name="path.root.web"}',
			DIR_STATIC_SKIN 		= '{cfg name="path.static.skin"}',
			DIR_STATIC_FRAMEWORK 	= '{cfg name="path.static.framework"}',
			LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}',
			SESSION_ID				= '{$_sPhpSessionId}',
			LANGUAGE				= '{$oConfig->GetValue('lang.current')}',
			WYSIWYG					= {if $oConfig->GetValue('view.wysiwyg')}true{else}false{/if};

		var aRouter = [];
		{foreach from=$aRouter key=sPage item=sPath}
			aRouter['{$sPage}'] = '{$sPath}';
		{/foreach}
	</script>

	{**
	 * JavaScript файлы
	 * JS файлы подключаются в конфиге шаблона (ваш_шаблон/settings/config.php)
	 *}
	{$aHtmlHeadFiles.js}

	<script>
		ls.lang.load({json var = $aLangJs});
		ls.lang.load({lang_load name="blog"});

		ls.registry.set('comment_max_tree', {json var=$oConfig->Get('module.comment.max_tree')});
		ls.registry.set('block_stream_show_tip', {json var=$oConfig->Get('block.stream.show_tip')});
	</script>

	{**
	 * Тип сетки сайта
	 *}
	{if {cfg name='view.grid.type'} == 'fluid'}
		<style>
			#container {
				min-width: {cfg name='view.grid.fluid_min_width'}px;
				max-width: {cfg name='view.grid.fluid_max_width'}px;
			}
		</style>
	{else}
		<style>
			#container { width: {cfg name='view.grid.fixed_width'}px; } {* *}
		</style>
	{/if}

	{block name='layout_head_end'}{/block}
	{hook run='html_head_end'}
</head>


{**
 * Вспомогательные классы
 *
 * ls-user-role-guest        Посетитель - гость
 * ls-user-role-user         Залогиненый пользователь - обычный пользователь
 * ls-user-role-admin        Залогиненый пользователь - админ
 * ls-user-role-not-admin    Залогиненый пользователь - не админ
 * ls-template-*             Класс с названием активного шаблона
 *}
{if $oUserCurrent}
	{$sBodyClasses = $sBodyClasses|cat:' ls-user-role-user'}

	{if $oUserCurrent->isAdministrator()}
		{$sBodyClasses = $sBodyClasses|cat:' ls-user-role-admin'}
	{/if}
{else}
	{$sBodyClasses = $sBodyClasses|cat:' ls-user-role-guest'}
{/if}

{if !$oUserCurrent or ($oUserCurrent and ! $oUserCurrent->isAdministrator())}
	{$sBodyClasses = $sBodyClasses|cat:' ls-user-role-not-admin'}
{/if}

{$sBodyClasses = $sBodyClasses|cat:' ls-template-'|cat:{cfg name="view.skin"}}


<body class="{$sBodyClasses} layout-{cfg name='view.grid.type'} {block name='layout_body_classes'}{/block}">
	{hook run='body_begin'}

	{block name='layout_body'}
		<div id="container" class="{hook run='container_class'} {if $bNoSidebar}no-sidebar{/if}">
			{* Шапка *}
			{include file='header.tpl'}


			{* Основная навигация *}
			<nav id="nav">
				<ul class="nav nav-main">
					<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
					<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
					<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
					<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a></li>

					{hook run='main_menu_item'}
				</ul>

				{hook run='main_menu'}
			</nav>


			{* Вспомогательный контейнер-обертка *}
			<div id="wrapper" class="{hook run='wrapper_class'}">
				{* Контент *}
				<div id="content-wrapper">
					<div id="content" 
						 role="main"
						 {if $sMenuItemSelect == 'profile'}itemscope itemtype="http://data-vocabulary.org/Person"{/if}>

						{hook run='content_begin'}
						{block name='layout_content_begin'}{/block}

						{block name='layout_page_title' hide}
							<h2 class="page-header">{$smarty.block.child}</h2>
						{/block}

						{* Навигация *}
						{if $sNav or $sNavContent}
							<div class="nav-group">
								{if $sNav}
									{if in_array($sNav, $aMenuContainers)}
										{$aMenuFetch.$sNav}
									{else}
										{include file="navs/nav.$sNav.tpl"}
									{/if}
								{else}
									{include file="navs/nav.$sNavContent.content.tpl"}
								{/if}
							</div>
						{/if}

						{* Системные сообщения *}
						{include file='system_message.tpl'}

						{block name='layout_content'}{/block}

						{block name='layout_content_end'}{/block}
						{hook run='content_end'}
					</div>
				</div>


				{* Сайдбар *}
				{if ! $bNoSidebar}
					<aside id="sidebar" role="complementary">
						{include file='blocks.tpl' group='right'}
					</aside>
				{/if}
			</div> {* /wrapper *}


			{* Подвал *}
			<footer id="footer">
				{hook run='footer_begin'}

				{block name='layout_footer_begin'}{/block}

				{hook run='copyright'}

				{block name='layout_footer_end'}{/block}

				{hook run='footer_end'}
			</footer>
		</div> {* /container *}
	{/block}


	{* Подключение модальных окон *}
	{if $oUserCurrent}
		{include file='modals/modal.create.tpl'}
		{include file='modals/modal.favourite_tags.tpl'}
	{else}
		{include file='modals/modal.auth.tpl'}
	{/if}


	{**
	 * Тулбар
	 * Добавление кнопок в тулбар
	 *}
	{add_block group='toolbar' name='toolbar/toolbar.admin.tpl' priority=100}
	{add_block group='toolbar' name='toolbar/toolbar.scrollup.tpl' priority=-100}

	{* Подключение тулбара *}
	{include file='toolbar/toolbar.tpl'}


	{hook run='body_end'}
</body>
</html>