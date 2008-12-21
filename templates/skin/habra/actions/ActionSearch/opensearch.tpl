<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"> 
	<ShortName>{$SITE_NAME}</ShortName> 
	<Description>{$sHtmlTitle}</Description> 
	<Contact>{$sAdminMail}</Contact> 
	<Url type="text/html" template="{$DIR_WEB_ROOT}/search/topics/?q={literal}{searchTerms}{/literal}" /> 
	<LongName>{$sHtmlDescription}</LongName> 
	<Image height="64" width="64" type="image/png">{$DIR_STATIC_SKIN}/img/logo.gif</Image> 
	<Image height="16" width="16" type="image/vnd.microsoft.icon">{$DIR_STATIC_SKIN}/img/favicon.ico</Image> 
	<Developer>{$SITE_NAME} ({$DIR_WEB_ROOT})</Developer> 
	<Attribution> 
		© «{$SITE_NAME}»
	</Attribution> 
	<SyndicationRight>open</SyndicationRight> 
	<AdultContent>false</AdultContent> 
	<Language>ru-ru</Language> 
	<OutputEncoding>UTF-8</OutputEncoding> 
	<InputEncoding>UTF-8</InputEncoding> 
</OpenSearchDescription>