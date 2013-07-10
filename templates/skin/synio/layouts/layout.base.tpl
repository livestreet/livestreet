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

	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href="{cfg name='path.static.assets'}/images/favicons/favicon.ico?v1" rel="shortcut icon" />
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
			DIR_ENGINE_LIBS	 		= '{cfg name="path.root.engine_lib"}',
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
		ls.lang.load({lang_load name="blog, talk_favourite_add, talk_favourite_del, topic_question_create_answers_error_max"});

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
		<div id="header-back"></div>
		
		<div id="container" class="{hook run='container_class'} {if $bNoSidebar}no-sidebar{/if}">
			{**
			 * Хидер сайта
			 *}
			<header id="header" role="banner">
				{hook run='header_banner_begin'}
				<h1 class="site-name"><a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a></h1>
				
				
				<ul class="nav nav-main" id="nav-main">
					<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a> <i></i></li>
					<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a> <i></i></li>
					<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a> <i></i></li>
					<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a> <i></i></li>

					{hook run='main_menu_item'}

					<li id="nav-main-more" class="nav-main-more">
						<a href="#" 
						   id="dropdown-mainmenu-trigger" 
						   class="dropdown-nav-main dropdown-toggle js-dropdown-default" 
						   data-type="dropdown-toggle" 
						   data-option-target="dropdown-mainmenu-menu"
						   data-option-align-x="right">{$aLang.more}</a>
					</li>
				</ul>

				<ul class="dropdown-menu dropdown-menu-nav-main" id="dropdown-mainmenu-menu"></ul>

				{hook run='main_menu'}
				
				
				{hook run='userbar_nav'}
				

				{**
				 * Юзербар
				 *}
				{if $oUserCurrent}
					<div class="dropdown-user" id="user-menu">
						<a href="{$oUserCurrent->getUserWebPath()}"><img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
						<a href="{$oUserCurrent->getUserWebPath()}" class="username">{$oUserCurrent->getLogin()}</a>
						
						<div class="dropdown-user-shadow"></div>
						<div class="dropdown-user-trigger js-dropdown-usermenu" data-type="dropdown-toggle" data-option-target="dropdown-user-menu"><i></i></div>
						
						<ul class="dropdown-user-menu" id="dropdown-user-menu" style="display: none" data-type="dropdown-target">
							<li class="item-stat">
								<span class="strength" title="{$aLang.user_skill}"><i class="icon-synio-star-green"></i> {$oUserCurrent->getSkill()}</span>
								<span class="rating {if $oUserCurrent->getRating() < 0}negative{/if}" title="{$aLang.user_rating}"><i class="icon-synio-rating"></i> {$oUserCurrent->getRating()}</span>
								{hook run='userbar_stat_item'}
							</li>
							{hook run='userbar_item_first'}
							<li class="item-messages">
								<a href="{router page='talk'}" id="new_messages">
									<i class="item-icon"></i>
									{$aLang.user_privat_messages}
									{if $iUserCurrentCountTalkNew}<div class="new">+{$iUserCurrentCountTalkNew}</div>{/if}
								</a>
							</li>
							<li class="item-favourite"><i class="item-icon"></i><a href="{$oUserCurrent->getUserWebPath()}favourites/topics/">{$aLang.user_menu_profile_favourites}</a></li> 
							<li class="item-profile"><i class="item-icon"></i><a href="{$oUserCurrent->getUserWebPath()}">{$aLang.footer_menu_user_profile}</a></li>
							<li class="item-settings"><i class="item-icon"></i><a href="{router page='settings'}profile/">{$aLang.user_settings}</a></li>
							<li class="item-create"><i class="item-icon"></i><a href="{router page='topic'}add/">{$aLang.block_create}</a></li>
							{hook run='userbar_item_last'}
							<li class="item-signout"><i class="item-icon"></i><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
						</ul>
					</div>
				{else}
					<ul class="auth">
						{hook run='userbar_item'}
						
						<li><a href="{router page='registration'}" data-type="modal-toggle" data-option-target="modal-login" onclick="jQuery('[data-option-target=tab-pane-registration]').tab('activate');">{$aLang.registration_submit}</a></li>
						<li><a href="{router page='login'}" data-type="modal-toggle" data-option-target="modal-login" onclick="jQuery('[data-option-target=tab-pane-login]').tab('activate');">{$aLang.user_login_submit}</a></li>
					</ul>
				{/if}
				
				{if $iUserCurrentCountTalkNew}<a href="{router page='talk'}" class="new-messages">+{$iUserCurrentCountTalkNew} <i class="icon-synio-new-message"></i></a>{/if}
				
				
				{hook run='header_banner_end'}
			</header>


			{* Навигация *}
			<nav id="nav">
				{if $sNav}
					{if in_array($sNav, $aMenuContainers)}
						{$aMenuFetch.$sNav}
					{else}
						{include file="navs/nav.$sNav.tpl"}
					{/if}
				{/if}
				
				{if $oUserCurrent}
					<a href="{router page='topic'}add/" class="button button-write" data-type="modal-toggle" data-option-target="modal-write">{$aLang.block_create}</a>
				{/if}
				
				{**
				 * Форма поиска
				 *
				 * @scripts js/init.js
				 *}
				<div class="search-header">
					<div class="search-header-show" id="search-header-show"><i class="icon-synio-search"></i> <a href="#" class="link-dotted">{$aLang.search_submit}</a></div>
					
					<form class="search-form" id="search-header-form" action="{router page='search'}topics/" style="display: none">
						<div class="search-form-search">
							<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="search-form-input width-250">
							<input type="submit" value="" title="{$aLang.search_submit}" class="search-form-submit">
						</div>
					</form>
				</div>
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
						{if $sNavContent}
							<div class="nav-group">
								{include file="navs/nav.$sNavContent.content.tpl"}
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
				{if $oUserCurrent}
					<ul class="footer-list">
						<li class="footer-list-header word-wrap">{$oUserCurrent->getLogin()}</li>
						<li><a href="{$oUserCurrent->getUserWebPath()}">{$aLang.footer_menu_user_profile}</a></li>
						<li><a href="{router page='settings'}profile/">{$aLang.user_settings}</a></li>
						<li><a href="{router page='topic'}add/" class="js-write-window-show">{$aLang.block_create}</a></li>
						{hook run='footer_menu_user_item' oUser=$oUserCurrent}
						<li><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
					</ul>
				{else}
					<ul class="footer-list">
						<li class="footer-list-header word-wrap">{$aLang.footer_menu_user_quest_title}</li>
						<li><a href="{router page='registration'}" class="js-registration-form-show">{$aLang.registration_submit}</a></li>
						<li><a href="{router page='login'}" class="js-login-form-show sign-in">{$aLang.user_login_submit}</a></li>
						{hook run='footer_menu_user_item' isGuest=true}
					</ul>
				{/if}
				
				<ul class="footer-list">
					<li class="footer-list-header">{$aLang.footer_menu_navigate_title}</li>
					<li><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
					<li><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
					<li><a href="{router page='people'}">{$aLang.people}</a></li>
					<li><a href="{router page='stream'}">{$aLang.stream_menu}</a></li>
					{hook run='footer_menu_navigate_item'}
				</ul>
				
				
				{* RU: Тут можно добавить свой блок со ссылками, расскоментируйте блок кода ниже и добавьте свои ссылки *}
				{* EN: You can add additional block with links here, just uncomment code below and add your links *}
				
				{*
					<ul class="footer-list">
						<li class="footer-list-header">{$aLang.footer_menu_project_title}</li>
						<li><a href="#">{$aLang.footer_menu_project_about}</a></li>
						<li><a href="#">{$aLang.footer_menu_project_contact}</a></li>
						<li><a href="#">{$aLang.footer_menu_project_advert}</a></li>
						<li><a href="#">{$aLang.footer_menu_project_help}</a></li>
						{hook run='footer_menu_project_item'}
					</ul>
				*}
			
				<div class="copyright">
					{hook run='copyright'}
					
					<div class="design-by">
						<i class="icon-xeoart"></i>
						Design by <a href="http://xeoart.com">xeoart</a>
						<div>2012</div>
					</div>
				</div>
				
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