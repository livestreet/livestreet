<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>{$sHtmlTitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<link rel="stylesheet" type="text/css" href="{$DIR_STATIC_SKIN}/css/style.css?v=1" />	
	{if $bRefreshToHome}
		<meta  HTTP-EQUIV="Refresh" CONTENT="3; URL={$DIR_WEB_ROOT}/">
	{/if}
</head>

<body>

<div id="container">
	<div id="header">
		<h1><a href="{$DIR_WEB_ROOT}">LiveStreet</a></h1>
	</div>
	
	{if !$noShowSystemMessage}
		{include file='system_message.tpl'}
	{/if}