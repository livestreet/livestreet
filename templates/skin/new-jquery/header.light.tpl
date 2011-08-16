<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="ru">

<head>
	{hook run='html_head_begin'}
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	
	{$aHtmlHeadFiles.css}
	
	{if $bRefreshToHome}
		<meta  HTTP-EQUIV="Refresh" CONTENT="3; URL={cfg name='path.root.web'}/">
	{/if}
	{hook run='html_head_end'}
</head>

<body>
{hook run='body_begin'}
<div id="container">
	<div id="header-light">
		<a href="{cfg name='path.root.web'}" class="logo">Live<span>Street</span></a>
	</div>
	
	{if !$noShowSystemMessage}
		{include file='system_message.tpl'}
	{/if}