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
        var		PATH_ROOT 					= '{cfg name="path.root.web"}',
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
                WYSIWYG					= {if $oConfig->GetValue('view.wysiwyg')}true{else}false{/if};

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
		ls.lang.load({lang_load name="blog, talk_favourite_add, talk_favourite_del, topic_question_create_answers_error_max, geo_select_city, geo_select_region, blog_create_type_open_notice, blog_create_type_close_notice"});

		ls.registry.set('comment_max_tree', {json var=$oConfig->Get('module.comment.max_tree')});
		ls.registry.set('block_stream_show_tip', {json var=$oConfig->Get('block.stream.show_tip')});
	</script>

	{**
	 * Тип сетки сайта
	 *}
	{if {cfg name='view.grid.type'} == 'fluid'}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav-main,
			.grid-role-header .site-info,
			.grid-role-container {
				min-width: {cfg name='view.grid.fluid_min_width'}px;
				max-width: {cfg name='view.grid.fluid_max_width'}px;
			}
		</style>
	{else}
		<style>
			.grid-role-userbar,
			.grid-role-nav .nav-main,
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

{if !$oUserCurrent or ($oUserCurrent and ! $oUserCurrent->isAdministrator())}
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

				<ul class="nav nav-userbar">
					{if $oUserCurrent}
						<li class="nav-userbar-username">
							<a href="{$oUserCurrent->getUserWebPath()}" class="dropdown-toggle js-dropdown-default" data-dropdown-target="js-dropdown-usermenu">
								<img src="{$oUserCurrent->getProfileAvatarPath(24)}" alt="{$oUserCurrent->getLogin()}" class="avatar" />
								{$oUserCurrent->getLogin()}
							</a>
						</li>
						<li><a href="{router page='topic'}add/" data-modal-target="modal-write">{$aLang.block_create}</a></li>

						{if $iUserCurrentCountTalkNew} 
							<li class="new-messages"><a href="{router page='talk'}" title="{if $iUserCurrentCountTalkNew}{$aLang.user_privat_messages_new}{/if}">{$aLang.user_privat_messages} +{$iUserCurrentCountTalkNew}</a></li>
						{/if}

						{hook run='userbar_item'}
						<li><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
					{else}
						{hook run='userbar_item'}
						<li><a href="{router page='login'}" class="js-modal-toggle-login">{$aLang.user_login_submit}</a></li>
						<li><a href="{router page='registration'}" class="js-modal-toggle-registration">{$aLang.registration_submit}</a></li>
					{/if}
				</ul>

				{if $oUserCurrent}
					{* User Menu *}

					<ul class="dropdown-menu" id="js-dropdown-usermenu">
						{* TODO: Add hooks *}
						{* TODO: Add counters *}

						<li {if $sAction=='profile' && ($aParams[0]=='whois' or $aParams[0]=='')}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}">{$aLang.user_menu_profile}</a></li>
						<li {if $sAction=='profile' && $aParams[0]=='wall'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}wall/">{$aLang.user_menu_profile_wall}</a></li>
						<li {if $sAction=='profile' && $aParams[0]=='created'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}created/topics/">{$aLang.user_menu_publication}</a></li>
						<li {if $sAction=='profile' && $aParams[0]=='favourites'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}favourites/topics/">{$aLang.user_menu_profile_favourites}</a></li>
						<li {if $sAction=='profile' && $aParams[0]=='friends'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}friends/">{$aLang.user_menu_profile_friends}</a></li>
						<li {if $sAction=='profile' && $aParams[0]=='stream'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}stream/">{$aLang.user_menu_profile_stream}</a></li>
						
						<li {if $sAction=='talk'}class="active"{/if}><a href="{router page='talk'}">{$aLang.talk_menu_inbox} {if $iUserCurrentCountTalkNew}<strong>+{$iUserCurrentCountTalkNew}</strong>{/if}</a></li>
						<li {if $sAction=='settings'}class="active"{/if}><a href="{router page='settings'}">{$aLang.settings_menu}</a></li>

						{if $oUserCurrent and $oUserCurrent->isAdministrator()}
							<li {if $sAction=='admin'}class="active"{/if}><a href="{router page='admin'}">{$aLang.admin_title}</a></li>
						{/if}
					</ul>
				{/if}

				<form action="{router page='search'}topics/" class="search-form">
					<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="search-form-input width-full">
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
				<h1 class="site-name"><a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a></h1>
				<h2 class="site-description">{cfg name='view.description'}</h2>
			</div>
			
			{hook run='header_banner_end'}
		</header>


		{* Основная навигация *}
		<nav class="grid-row grid-role-nav">
			<ul class="nav nav-main">
				<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
				<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
				<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
				<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a></li>

				{hook run='main_menu_item'}
			</ul>

			{hook run='main_menu'}
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
					{if ! $bNoSystemMessages}
						{if $aMsgError}
							{include file='alert.tpl' sAlertStyle='error' mAlerts=$aMsgError bAlertClose=true}
						{/if}

						{if $aMsgNotice}
							{include file='alert.tpl' mAlerts=$aMsgNotice bAlertClose=true}
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