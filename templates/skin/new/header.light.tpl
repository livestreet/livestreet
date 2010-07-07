<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

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
	<h1 class="lite-header"><a href="{cfg name='path.root.web'}">Live<span>Street</span></a></h1>
	
	{if !$noShowSystemMessage}
		{include file='system_message.tpl'}
	{/if}