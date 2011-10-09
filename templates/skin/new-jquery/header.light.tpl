<!DOCTYPE HTML>

<html>

<head>
	{hook run='html_head_begin'}
	<title>{$sHtmlTitle}</title>
	<meta charset="UTF-8" />
	
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