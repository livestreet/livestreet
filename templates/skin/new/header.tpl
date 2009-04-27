<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<meta name="DESCRIPTION" content="{$sHtmlDescription}" />
	<meta name="KEYWORDS" content="{$sHtmlKeywords}" />	
	
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/style.css?v=1" />
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/ie6.css?v=1" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/ie7.css?v=1" /><![endif]-->	
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/Roar.css" />
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/piechart.css" />
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/Autocompleter.css" />
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/prettify.css" />
	<!--[if gt IE 6]><link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/simple_comments.css" /><![endif]-->
		
	<link href="{$DIR_STATIC_SKIN}/images/favicon.ico" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/opensearch/" title="{$SITE_NAME}" />
	
	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}
</head>

<script language="JavaScript" type="text/javascript">
var DIR_WEB_ROOT='{$DIR_WEB_ROOT}';
var DIR_STATIC_SKIN='{$DIR_STATIC_SKIN}';
var BLOG_USE_TINYMCE='{$BLOG_USE_TINYMCE}';
</script>

<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/mootools-1.2.js?v=1.2.2"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Roal/Roar.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Observer.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js"></script>
<!--[if IE]>
	<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Piechart/moocanvas.js"></script>
<![endif]-->	
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Piechart/piechart.js"></script>

<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/prettify/prettify.js"></script>

<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/vote.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/favourites.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/questions.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/block_loader.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/friend.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/blog.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/other.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/login.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/panel.js"></script>


{literal}
<script language="JavaScript" type="text/javascript">
var tinyMCE=false;
var msgErrorBox=new Roar({
			position: 'upperRight',
			className: 'roar-error',
			margin: {x: 30, y: 10}
		});	
var msgNoticeBox=new Roar({
			position: 'upperRight',
			className: 'roar-notice',
			margin: {x: 30, y: 10}
		});	
</script>
{/literal}



<body onload="prettyPrint()">



<div id="debug" style="border: 2px #dd0000 solid; display: none;"></div>

<div id="container">
	
	{include file=header_top.tpl}	
	
	{include file=header_nav.tpl}
	
	<!--
	<div id="extra">
		<a href="#">К списку постов</a>
	</div>
	-->
	
	<div id="wrapper" class="{if !$showUpdateButton}update-hide{/if} {if $showWhiteBack}white-back{/if}">
		
	
		<!-- Content -->
		<div id="content" {if $bNoSidebar}style="width:100%;"{/if}>
		
		{if !$noShowSystemMessage}
			{include file='system_message.tpl'}
		{/if}