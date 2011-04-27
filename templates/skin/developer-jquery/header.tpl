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
	
	<script>
		var DIR_WEB_ROOT 			= '{cfg name="path.root.web"}';
		var DIR_STATIC_SKIN 		= '{cfg name="path.static.skin"}';
		var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';
		
		var LANG_JOIN 				= '{$aLang.blog_join}';
		var LANG_LEAVE 				= '{$aLang.blog_leave}';
		var LANG_DELETE 			= '{$aLang.blog_delete}';
		var LANG_POLL_ERROR 		= '{$aLang.topic_question_create_answers_error_max}';

		var IMG_PATH_LOADER 		= DIR_STATIC_SKIN + '/images/loader.gif';
		
		var aRouter = new Array();
		{foreach from=$aRouter key=sPage item=sPath}
			aRouter['{$sPage}'] = '{$sPath}';
		{/foreach}
	</script>

	{$aHtmlHeadFiles.js}

	{hook run='html_head_end'}
</head>


<body>
	{hook run='body_begin'}

	<div id="container">
		{include file='header_top.tpl'}

		<div id="wrapper">
			<div id="content">
				{include file='window_login.tpl'}
				{include file='nav.tpl'}
				{include file='system_message.tpl'}
				
				{hook run='content_begin'}