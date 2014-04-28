<!doctype html>

{block name='layout_options'}{/block}

{$sRTL = ( $oConfig->GetValue('view.rtl') ) ? 'dir="rtl"' : ''}
{$sLang = $oConfig->GetValue('lang.current')}

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="{$sLang}" {$sRTL}> <!--<![endif]-->

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

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href="{cfg name='path.skin.assets.web'}/images/favicons/favicon.ico?v1" rel="shortcut icon" />
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
        var		PATH_ROOT 					= '{router page='/'}',
                PATH_SKIN		 			= '{cfg name="path.skin.web"}',
                PATH_FRAMEWORK_FRONTEND		= '{cfg name="path.framework.frontend.web"}',
                PATH_FRAMEWORK_LIBS_VENDOR	= '{cfg name="path.framework.libs_vendor.web"}',
                /**
                 * Для совместимости с прошлыми версиями. БУДУТ УДАЛЕНЫ
                 */
                DIR_WEB_ROOT 				= '{cfg name="path.root.web"}',
                DIR_STATIC_SKIN 			= '{cfg name="path.skin.web"}',
                DIR_STATIC_FRAMEWORK 		= '{cfg name="path.framework.frontend.web"}',
                DIR_ENGINE_LIBS	 			= '{cfg name="path.framework.web"}/libs',

                LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}',
                SESSION_ID				= '{$_sPhpSessionId}',
                SESSION_NAME			= '{$_sPhpSessionName}',
                LANGUAGE				= '{$oConfig->GetValue('lang.current')}',
                WYSIWYG					= {if $oConfig->GetValue('view.wysiwyg')}true{else}false{/if},
                USER_PROFILE_LOGIN		= {if $oUserProfile}{json var=$oUserProfile->getLogin()}{else}''{/if};

		var aRouter = [];
		{foreach $aRouter as $sPage => $sPath}
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
		ls.lang.load({lang_load name="comments.comments_declension, comments.folding.unfold, comments.folding.fold, poll.notices.error_answers_max, blog.blog, favourite.add, favourite.remove, geo_select_city, geo_select_region, blog.add.fields.type.note_open, blog.add.fields.type.note_close, common.success.add, common.success.remove"});

		ls.registry.set('comment_max_tree', {json var=$oConfig->Get('module.comment.max_tree')});
		ls.registry.set('block_stream_show_tip', {json var=$oConfig->Get('block.stream.show_tip')});
	</script>

	{**
	 * Тип сетки сайта
	 *}
	{if {cfg name='view.grid.type'} == 'fluid'}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav--main,
			.grid-role-header .site-info,
			.grid-role-container {
				min-width: {cfg name='view.grid.fluid_min_width'}px;
				max-width: {cfg name='view.grid.fluid_max_width'}px;
			}
		</style>
	{else}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav--main,
			.grid-role-header .site-info,
			.grid-role-container { width: {cfg name='view.grid.fixed_width'}px; }
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

{if !$oUserCurrent or !$oUserCurrent->isAdministrator()}
	{$sBodyClasses = $sBodyClasses|cat:' ls-user-role-not-admin'}
{/if}

{$sBodyClasses = $sBodyClasses|cat:' ls-template-'|cat:{cfg name="view.skin"}}


<body class="{$sBodyClasses} layout-{cfg name='view.grid.type'} {block name='layout_body_classes'}{/block}">
	{hook run='body_begin'}

	{block name='layout_body'}
		{**
		 * Юзербар
		 *}
		<div class="grid-role-userbar-wrapper">
			<nav class="grid-row grid-role-userbar">
				{hook run='userbar_nav'}

				{include 'navs/nav.userbar.tpl'}

				<form action="{router page='search'}topics/" class="search-form">
					<input type="text" placeholder="{$aLang.search.search}" maxlength="255" name="q" class="search-form-input width-full">
					<input type="submit" value="" title="{$aLang.search_submit}" class="search-form-submit icon-search">
				</form>
			</nav>
		</div>


		{**
		 * Шапка
		 *}
		<header class="grid-row grid-role-header" role="banner">
			{hook run='header_banner_begin'}

			<div class="site-info">
				<h1 class="site-name"><a href="{router page='/'}">{cfg name='view.name'}</a></h1>
				<h2 class="site-description">{cfg name='view.description'}</h2>
			</div>

			{hook run='header_banner_end'}
		</header>


		{* Основная навигация *}
		<nav class="grid-row grid-role-nav">
			{include 'navs/nav.main.tpl'}
		</nav>

		<div id="container" class="grid-row grid-role-container {hook run='container_class'} {if $bNoSidebar}no-sidebar{/if}">
			{* Вспомогательный контейнер-обертка *}
			<div class="grid-row grid-role-wrapper" class="{hook run='wrapper_class'}">
				{* Контент *}
				<div class="grid-col grid-col-8 grid-role-content"
					 role="main"
					 {if $sMenuItemSelect == 'profile'}itemscope itemtype="http://data-vocabulary.org/Person"{/if}>

					{hook run='content_begin'}
					{block name='layout_content_begin'}{/block}

					{* Основной заголовок страницы *}
					{block name='layout_page_title' hide}
						<h2 class="page-header">{$smarty.block.child}</h2>
					{/block}

					{* Навигация *}
					{if $sNav}
						<div class="nav-group">
							{if $sNav}
								{if in_array($sNav, $aMenuContainers)}
									{$aMenuFetch.$sNav}
								{else}
									{include file="navs/nav.$sNav.tpl"}
								{/if}
							{/if}
						</div>
					{/if}

					{* Системные сообщения *}
					{if ! $bNoSystemMessages}
						{if $aMsgError}
							{include 'components/alert/alert.tpl' sMods='error' mAlerts=$aMsgError bClose=true}
						{/if}

						{if $aMsgNotice}
							{include 'components/alert/alert.tpl' mAlerts=$aMsgNotice bClose=true}
						{/if}
					{/if}

					{* Контент *}
					{block name='layout_content'}{/block}

					{block name='layout_content_end'}{/block}
					{hook run='content_end'}
				</div>


				{* Сайдбар *}
				{if ! $bNoSidebar}
					<aside class="grid-col grid-col-4 grid-role-sidebar" role="complementary">
						{include file='blocks.tpl' group='right'}
					</aside>
				{/if}
			</div> {* /wrapper *}


			{* Подвал *}
			<footer class="grid-row grid-role-footer">
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