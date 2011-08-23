<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="ru">
<head>
	{hook run='html_head_begin'}
	
	<title>{$sHtmlTitle}</title>
	
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="{$sHtmlDescription}" />
	<meta name="keywords" content="{$sHtmlKeywords}" />

	{$aHtmlHeadFiles.css}

	<link href="{cfg name='path.static.skin'}/images/favicon.ico" rel="shortcut icon" />
	<link rel="search" type="application/opensearchdescription+xml" href="{router page='search'}opensearch/" title="{cfg name='view.name'}" />

	{if $aHtmlRssAlternate}
		<link rel="alternate" type="application/rss+xml" href="{$aHtmlRssAlternate.url}" title="{$aHtmlRssAlternate.title}">
	{/if}
	
	{if $bRefreshToHome}
		<meta  HTTP-EQUIV="Refresh" CONTENT="3; URL={cfg name='path.root.web'}/">
	{/if}
	
	<script language="JavaScript" type="text/javascript">
	var DIR_WEB_ROOT 			= '{cfg name="path.root.web"}';
	var DIR_STATIC_SKIN 		= '{cfg name="path.static.skin"}';
	var DIR_ROOT_ENGINE_LIB     = '{cfg name="path.root.engine_lib"}'; 
	var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';
	var SESSION_ID              = '{$_sPhpSessionId}'; 
	var BLOG_USE_TINYMCE		= '{cfg name="view.tinymce"}';

	var TINYMCE_LANG='en';
	{if $oConfig->GetValue('lang.current')=='russian'}
		TINYMCE_LANG='ru';
	{/if}

	var aRouter = new Array();
	{foreach from=$aRouter key=sPage item=sPath}
		aRouter['{$sPage}'] = '{$sPath}';
	{/foreach}
	</script>

	{$aHtmlHeadFiles.js}
    
	<script language="JavaScript" type="text/javascript">
		var tinyMCE=false;
		ls.lang.load({json var=$aLangJs});
	</script>
	
	{hook run='html_head_end'}
</head>


<body onload="prettyPrint()">
	{hook run='body_begin'}
	{include file='header_top.tpl'}
	{include file='nav.tpl'}

	<div id="container">
		<div id="wrapper" class="{if $oUserCurrent and $showUpdateButton}show-update-button{/if} {if $showWhiteBack}white-back{/if}">
			<div id="content" {if $noSidebar}style="width: 100%"{/if}>
				<div id="content-inner">
					{include file='window_login.tpl'}
					{include file='system_message.tpl'}
					
					{hook run='content_begin'}