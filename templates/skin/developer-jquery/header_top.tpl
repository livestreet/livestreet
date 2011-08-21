<div id="header">
	<div class="profile">
		{if $oUserCurrent}
			<a href="{$oUserCurrent->getUserWebPath()}" class="username">{$oUserCurrent->getLogin()}</a> |
			<a href="{router page='topic'}add/" class="create">{$aLang.topic_create}</a> |
			{if $iUserCurrentCountTalkNew}
				<a href="{router page='talk'}" class="message-new" id="new_messages" title="{$aLang.user_privat_messages_new}">{$aLang.user_privat_messages} ({$iUserCurrentCountTalkNew})</a>  |
			{else}
				<a href="{router page='talk'}" id="new_messages">{$aLang.user_privat_messages} ({$iUserCurrentCountTalkNew})</a> |
			{/if}
			<a href="{router page='settings'}profile/">{$aLang.user_settings}</a> |
			<a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a>
			{hook run='userbar_item'}
		{else}
			<a href="{router page='login'}" id="login_form_show">{$aLang.user_login_submit}</a> |
			<a href="{router page='registration'}">{$aLang.registration_submit}</a>
		{/if}
		
		
		<form action="{router page='search'}topics/" method="GET" class="search">
			<input class="text" type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
			<input class="button" type="submit" value="{$aLang.search_submit}" />
		</form>
	</div>
	

	<h1><a href="{cfg name='path.root.web'}">LiveStreet</a></h1>
	
	
	<ul class="pages">
		<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
		<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
		<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
		<li {if $sMenuItemSelect=='top'}class="active"{/if}><a href="{router page='top'}">{$aLang.blog_menu_top}</a></li>
		{if $oUserCurrent}
			<li {if $sMenuItemSelect=='stream'}class="active"{/if}>
				<a href="{router page='stream'}">{$aLang.stream_personal_title}</a>
			</li>
		{/if}
						
		{hook run='main_menu'}
	</ul>
</div>