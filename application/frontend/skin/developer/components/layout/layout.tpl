<!doctype html>

{block name='layout_options'}{/block}

{$sRTL = ( Config::Get('view.rtl') ) ? 'dir="rtl"' : ''}
{$sLang = Config::Get('lang.current')}

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="{$sLang}" {$sRTL}> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="{$sLang}" {$sRTL}> <!--<![endif]-->

<head>
	{block name='layout_head'}
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<meta name="description" content="{block name='layout_description'}{$sHtmlDescription}{/block}">
		<meta name="keywords" content="{block name='layout_keywords'}{$sHtmlKeywords}{/block}">

		<title>{block name='layout_title'}{$sHtmlTitle}{/block}</title>

		{* RSS *}
		{if $aHtmlRssAlternate}
			<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
		{/if}

		{* Canonical *}
		{if $sHtmlCanonical}
			<link rel="canonical" href="{$sHtmlCanonical}" />
		{/if}

		{**
		 * Стили
		 * CSS файлы подключаются в конфиге шаблона (ваш_шаблон/settings/config.php)
		 *}
		{block name='layout_head_styles'}
			{* Подключение стилей указанных в конфиге *}
			{$aHtmlHeadFiles.css}

			<link href="{cfg name='path.skin.assets.web'}/images/favicons/favicon.ico?v1" rel="shortcut icon" />
			<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{cfg name='view.name'}" />
		{/block}

		{**
		 * JavaScript файлы
		 * JS файлы подключаются в конфиге шаблона (ваш_шаблон/settings/config.php)
		 *}
		{block name='layout_head_scripts'}
			<script>
				var	PATH_ROOT 					= '{router page='/'}',
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
					LANGUAGE				= '{Config::Get('lang.current')}',
					WYSIWYG					= {if Config::Get('view.wysiwyg')}true{else}false{/if},
					USER_PROFILE_LOGIN		= {if $oUserProfile}{json var=$oUserProfile->getLogin()}{else}''{/if};

				var aRouter = [];
				{foreach $aRouter as $sPage => $sPath}
					aRouter['{$sPage}'] = '{$sPath}';
				{/foreach}
			</script>

			{* Подключение скриптов указанных в конфиге *}
			{$aHtmlHeadFiles.js}
		{/block}
	{/block}

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
	{block name='layout_body'}{/block}

	{$sLayoutAfter}
</body>
</html>