{if !$oUserCurrent}
	<div class="login-form">
		<a href="#" class="close" onclick="hideLoginForm(); return false;"></a>
		
		<form action="{router page='login'}" method="POST">
			<h3>{$aLang.user_authorization}</h3>

			{hook run='form_login_popup_begin'}

			<p><label>{$aLang.user_login}:<br />
			<input type="text" class="input-text" name="login" id="login-input"/></label></p>
			
			<p><label>{$aLang.user_password}:<br />
			<input type="password" name="password" class="input-text" /></label></p>
			
			<p><label><input type="checkbox" name="remember" class="checkbox" checked />{$aLang.user_login_remember}</label></p>

			{hook run='form_login_popup_end'}

			<input type="submit" name="submit_login" value="{$aLang.user_login_submit}" /><br /><br />
			
			<a href="{router page='login'}reminder/">{$aLang.user_password_reminder}</a><br />
			<a href="{router page='registration'}">{$aLang.user_registration}</a>
		</form>
	</div>
{/if}


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
			<a href="{router page='login'}" onclick="showLoginForm(); return false;">{$aLang.user_login_submit}</a> |
			<a href="{router page='registration'}">{$aLang.registration_submit}</a>
		{/if}
	</div>

	
	<h1><a href="{cfg name='path.root.web'}">LiveStreet</a></h1>
	
	
	<ul class="pages">
		<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}">{$aLang.clean_posts}</a></li>
		<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
		<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
						
		{hook run='main_menu'}
	</ul>
</div>