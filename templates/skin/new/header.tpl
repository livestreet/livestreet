<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<meta name="DESCRIPTION" content="{$sHtmlDescription}" />
	<meta name="KEYWORDS" content="{$sHtmlKeywords}" />	
	
	<link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/style.css?v=1" />
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/ie6.css?v=1" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/ie7.css?v=1" /><![endif]-->	
	<link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/Roar.css" />
	<link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/piechart.css" />
	<link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/Autocompleter.css" />
	<link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/prettify.css" />
	<!--[if gt IE 6]><link rel="stylesheet" type="text/css" href="{$aConfig.path.static.skin}/css/simple_comments.css" /><![endif]-->
		
	<link href="{$aConfig.path.static.skin}/images/favicon.ico" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{$aConfig.view.name}" />
	
	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}
</head>

<script language="JavaScript" type="text/javascript">
var DIR_WEB_ROOT='{$aConfig.path.root.web}';
var DIR_STATIC_SKIN='{$aConfig.path.static.skin}';
var BLOG_USE_TINYMCE='{$aConfig.view.tinymce}';
var TALK_RELOAD_PERIOD = '{$aConfig.module.talk.period}';
var TALK_RELOAD_REQUEST = '{$aConfig.module.talk.request}'; 
</script>

<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/mootools-1.2.js?v=1.2.2"></script>
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Roal/Roar.js"></script>
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Autocompleter/Observer.js"></script>
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js"></script>
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js"></script>
<!--[if IE]>
	<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Piechart/moocanvas.js"></script>
<![endif]-->	
<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/MooTools_1.2/plugs/Piechart/piechart.js"></script>

<script type="text/javascript" src="{$aConfig.path.root.engine_lib}/external/prettify/prettify.js"></script>

<script type="text/javascript" src="{$aConfig.path.static.skin}/js/vote.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/favourites.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/questions.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/block_loader.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/friend.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/blog.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/other.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/login.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/panel.js"></script>
<script type="text/javascript" src="{$aConfig.path.static.skin}/js/messages.js"></script>

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

{if $oUserCurrent && $aConfig.module.talk.reload}
{literal}
<script language="JavaScript" type="text/javascript">
    var talkNewMessages=new lsTalkMessagesClass({
    	reload: {
            request: TALK_RELOAD_REQUEST,
        	url: DIR_WEB_ROOT+'/include/ajax/talkNewMessages.php',
    	}
    });  
	(function(){
   		talkNewMessages.get();
	}).periodical(TALK_RELOAD_PERIOD);
</script>
{/literal}
{/if}

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