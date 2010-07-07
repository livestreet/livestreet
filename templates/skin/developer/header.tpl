<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

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

	<script language="JavaScript" type="text/javascript">
		var DIR_WEB_ROOT='{cfg name="path.root.web"}';
		var DIR_STATIC_SKIN='{cfg name="path.static.skin"}';
		var BLOG_USE_TINYMCE='{cfg name="view.tinymce"}';
		var TALK_RELOAD_PERIOD='{cfg name="module.talk.period"}';
		var TALK_RELOAD_REQUEST='{cfg name="module.talk.request"}';
		var TALK_RELOAD_MAX_ERRORS='{cfg name="module.talk.max_errors"}';
		var LIVESTREET_SECURITY_KEY = '{$LIVESTREET_SECURITY_KEY}';

		var TINYMCE_LANG='en';
		{if $oConfig->GetValue('lang.current')=='russian'}
			TINYMCE_LANG='ru';
		{/if}

		var aRouter=new Array();
		{foreach from=$aRouter key=sPage item=sPath}
			aRouter['{$sPage}']='{$sPath}';
		{/foreach}

		var LANG_JOIN = '{$aLang.clean_join}';
		var LANG_LEAVE = '{$aLang.clean_leave}';
		var LANG_COMMENT_FOLD = '{$aLang.comment_fold}';
		var LANG_COMMENT_UNFOLD = '{$aLang.comment_unfold}';
		var LANG_BLOG_DELETE = '{$aLang.blog_delete}';
	</script>


	{$aHtmlHeadFiles.js}


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

	{if $oUserCurrent && $oConfig->GetValue('module.talk.reload')}
		{literal}
		<script language="JavaScript" type="text/javascript">
			var talkNewMessages=new lsTalkMessagesClass({
				reload: {
					request: TALK_RELOAD_REQUEST,
					url: DIR_WEB_ROOT+'/include/ajax/talkNewMessages.php',
					errors: TALK_RELOAD_MAX_ERRORS
				}
			});
			(function(){
				talkNewMessages.get();
			}).periodical(TALK_RELOAD_PERIOD);
		</script>
		{/literal}
	{/if}
	
	{hook run='html_head_end'}
</head>





<body>

{hook run='body_begin'}

<div id="container">
	{include file=header_top.tpl}

	<div id="wrapper">
		<div id="content" {if $noSidebar}style="width: 100%"{/if}>
			{include file=header_nav.tpl}

			{if !$noShowSystemMessage}
				{include file='system_message.tpl'}
			{/if}
			{hook run='content_begin'}