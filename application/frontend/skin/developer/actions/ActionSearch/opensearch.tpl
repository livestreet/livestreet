<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>{Config::Get('view.name')}</ShortName>
    <Description>{$sHtmlTitle}</Description>
    <Contact>{Config::Get('sys.mail.from_email')}</Contact>
    <Url type="text/html" template="{router page='search/topics'}?q={literal}{searchTerms}{/literal}" />
    <LongName>{$sHtmlDescription}</LongName>
    <Image height="64" width="64" type="image/png">{Config::Get('path.skin.assets.web')}/images/favicons/opensearch.png</Image>
    <Image height="16" width="16" type="image/vnd.microsoft.icon">{Config::Get('path.skin.assets.web')}/images/favicons/favicon.ico</Image>
    <Developer>{Config::Get('view.name')} ({Router::GetPath('/')})</Developer>
    <Attribution>
        © «{Config::Get('view.name')}»
    </Attribution>
    <SyndicationRight>open</SyndicationRight>
    <AdultContent>false</AdultContent>
    <Language>ru-ru</Language>
    <OutputEncoding>UTF-8</OutputEncoding>
    <InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>