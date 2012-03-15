<nav id="userbar" class="clearfix">
	<form action="{router page='search'}topics/" class="search">
		<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
		<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search">
	</form>
	
	
	<ul class="nav nav-userbar">
		{if $oUserCurrent}
			<li><a href="{$oUserCurrent->getUserWebPath()}" class="username">{$oUserCurrent->getLogin()}</a></li>
			<li><a href="{router page='topic'}add/" class="create">{$aLang.topic_create}</a></li>
			
			{if $iUserCurrentCountTalkNew}
				<li><a href="{router page='talk'}" class="message-new" id="new_messages" title="{$aLang.user_privat_messages_new}">{$aLang.user_privat_messages} ({$iUserCurrentCountTalkNew})</a></li>
			{else}
				<li><a href="{router page='talk'}" id="new_messages">{$aLang.user_privat_messages} ({$iUserCurrentCountTalkNew})</a></li>
			{/if}
			
			<li><a href="{router page='settings'}profile/">{$aLang.user_settings}</a></li>
			<li><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
			
			{hook run='userbar_item'}
		{else}
			<li><a href="{router page='login'}" id="login_form_show">{$aLang.user_login_submit}</a></li>
			<li><a href="{router page='registration'}">{$aLang.registration_submit}</a></li>
		{/if}
	</ul>
</nav>


<header id="header" role="banner">
	<hgroup class="site-info">
		<h1 class="site-name"><a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a></h1>
		<h2 class="site-description">{cfg name='view.description'}</h2>
	</hgroup>
</header>