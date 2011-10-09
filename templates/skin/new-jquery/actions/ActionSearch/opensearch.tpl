<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"> 
	<ShortName>{cfg name='view.name'}</ShortName> 
	<Description>{$sHtmlTitle}</Description> 
	<Contact>{$sAdminMail}</Contact> 
	<Url type="text/html" template="{router page='search'}topics/?q={literal}{searchTerms}{/literal}" /> 
	<LongName>{$sHtmlDescription}</LongName> 
	<Image height="64" width="64" type="image/png">{cfg name='path.static.skin'}/images/logo.png</Image> 
	<Image height="16" width="16" type="image/vnd.microsoft.icon">{cfg name='path.static.skin'}/images/favicon.ico</Image> 
	<Developer>{cfg name='view.name'} ({cfg name='path.root.web'})</Developer> 
	<Attribution> 
		© «{cfg name='view.name'}»
	</Attribution> 
	<SyndicationRight>open</SyndicationRight> 
	<AdultContent>false</AdultContent> 
	<Language>ru-ru</Language> 
	<OutputEncoding>UTF-8</OutputEncoding> 
	<InputEncoding>UTF-8</InputEncoding> 
</OpenSearchDescription>