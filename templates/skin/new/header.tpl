<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<meta name="DESCRIPTION" content="{$sHtmlDescription}" />
	<meta name="KEYWORDS" content="{$sHtmlKeywords}" />	
	
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/style.css" />
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/ie6.css" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/ie7.css" /><![endif]-->	
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/Roar.css" />
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/piechart.css" />
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/Autocompleter.css" />
		
	<link href="{$DIR_STATIC_SKIN}/images/favicon.ico" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{$DIR_WEB_ROOT}/search/opensearch/" title="{$SITE_NAME}" />
</head>

<script>
var DIR_WEB_ROOT='{$DIR_WEB_ROOT}';
var DIR_STATIC_SKIN='{$DIR_STATIC_SKIN}';
var BLOG_USE_TINYMCE='{$BLOG_USE_TINYMCE}';
</script>

<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/mootools-1.2-core-yc.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/mootools-more.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Roal/Roar.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Observer.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Clientcide/Clientcide.js"></script>
<!--[if IE]>
	<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Piechart/moocanvas.js"></script>
<![endif]-->	
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Piechart/piechart.js"></script>

<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/vote.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/favourites.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/questions.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/block_loader.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/friend.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/blog.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/other.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/login.js"></script>


{literal}
<script language="JavaScript" type="text/javascript">
var tinyMCE=false;
var msgErrorBox=new Roar({
			position: 'upperRight',
			className: 'roar-error'
		});	
var msgNoticeBox=new Roar({
			position: 'upperRight',
			className: 'roar-notice'
		});	
</script>
{/literal}



<body>



<div id="debug" style="border: 2px #dd0000 solid; display: none;"></div>

<div id="container">

	
	<div class="overlay"></div>
	{if !$oUserCurrent}
	<div class="lite-center login-popup" id="login-form">
		<div class="login-popup-top"><a href="#" class="close-block" onclick="hideLoginForm(); return false;"></a></div>
		<div class="content">
			<form action="{$DIR_WEB_ROOT}/login/" method="POST">
				<h3>Авторизация</h3>
				<div class="lite-note"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_REGISTRATION}/">Зарегистрироваться</a><label for="">Логин или эл. почта</label></div>
				<p><input type="text" class="input-text" name="login" tabindex="1" id="login-input"/></p>
				<div class="lite-note"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/reminder/" tabindex="-1">Напомнить пароль</a><label for="">Пароль</label></div>
				<p><input type="password" name="password" class="input-text" tabindex="2" /></p>
				<div class="lite-note"><input type="image" src="{$DIR_STATIC_SKIN}/images/login-button.gif" class="input-button" tabindex="4" /><label for="" class="input-checkbox"><input type="checkbox" name="remember" checked tabindex="3" > Запомнить меня</label></div>
				<input type="hidden" name="submit_login">
			</form>
		</div>
		<div class="login-popup-bottom"></div>
	</div>
	{/if}
	
	<!-- Header -->
	<div id="header">
		<h1><a href="{$DIR_WEB_ROOT}"><strong>Live</strong>Street</a></h1>
		
		<ul class="nav-main">
			<li class="active"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/">{$aLang.blogs}</a></li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people}</a></li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/about/">{$aLang.page_about}</a></li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/download/">{$aLang.page_download}</a></li>
		</ul>
		
		{if $oUserCurrent}
		<div class="profile">
			<a href="{$oUserCurrent->getUserWebPath()}" class="avatar"><img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="{$oUserCurrent->getLogin()}" /></a>
			<ul>
				<li><a href="{$oUserCurrent->getUserWebPath()}" class="author">{$oUserCurrent->getLogin()}</a> (<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/exit/">{$aLang.exit}</a>)</li>
				<li>
					{if $iUserCurrentCountTalkNew}
						<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/" class="message" title="{$aLang.user_privat_messages_new}">{$iUserCurrentCountTalkNew}</a> 
					{else}
						<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/" class="message">нет</a>
					{/if}
					{$aLang.user_settings} <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SETTINGS}/profile/">{$aLang.user_settings_profile}</a> | <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SETTINGS}/tuning/">{$aLang.user_settings_tuning}</a> 
				</li>
				<li>{$aLang.user_rating} <strong>{$oUserCurrent->getRating()}</strong></li>
			</ul>
		</div>
		{else}
		<a href="#" onclick="showLoginForm(); return false;">Войти</a>
		{/if}
		
		
	</div>
	<!-- /Header -->
	
	
	
	<!-- Navigation -->
	<div id="nav">
		<div class="left"></div>
		{if $oUserCurrent}
			<div class="write"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/add/" class="author"><img alt="{$aLang.topic_create}" title="{$aLang.topic_create}" src="{$DIR_STATIC_SKIN}/images/write.gif"/></a></div>
		{/if}
		
		{if $menu}
			{include file=menu.$menu.tpl}
		{/if}
	
				
		<div class="right"></div>
		<!--<a href="#" class="rss" onclick="return false;"></a>-->
		<div class="search">
			<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/topics/" method="post">
				<input class="text" type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
				<input class="button" type="submit" value="" />
			</form>
		</div>
	</div>
	<!-- /Navigation -->
	
	<!--
	<div id="extra">
		<a href="#">К списку постов</a>
	</div>
	-->
	
	<div id="wrapper" class="{if !$showUpdateButton}update-hide{/if} {if $showWhiteBack}white-back{/if}">
		
	
		<!-- Content -->
		<div id="content">
		
		{include file='system_message.tpl'}