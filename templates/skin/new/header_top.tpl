	<!-- Header -->
	{if !$oUserCurrent}	
	<div style="display: none;">
	<div class="login-popup" id="login-form">
		<div class="login-popup-top"><a href="#" class="close-block" onclick="return false;"></a></div>
		<div class="content">
			<form action="{router page='login'}" method="POST">
				<h3>{$aLang.user_authorization}</h3>
				<div class="lite-note"><a href="{router page='registration'}">{$aLang.registration_submit}</a><label for="">{$aLang.user_login}</label></div>
				<p><input type="text" class="input-text" name="login" tabindex="1" id="login-input"/></p>
				<div class="lite-note"><a href="{router page='login'}reminder/" tabindex="-1">{$aLang.user_password_reminder}</a><label for="">{$aLang.user_password}</label></div>
				<p><input type="password" name="password" class="input-text" tabindex="2" /></p>
				<div class="lite-note"><button type="submit" onfocus="blur()"><span><em>{$aLang.user_login_submit}</em></span></button><label for="" class="input-checkbox"><input type="checkbox" name="remember" checked tabindex="3" >{$aLang.user_login_remember}</label></div>
				<input type="hidden" name="submit_login">
			</form>
		</div>
		<div class="login-popup-bottom"></div>
	</div>
	</div>
	{/if}
	
	<div id="header">
		<h1><a href="{$aConfig.path.root.web}"><strong>Live</strong>Street</a></h1>
		
		<ul class="nav-main">
			<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}">{$aLang.blogs}</a></li>
			<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
			<li {if $sAction=='page' and $sEvent=='about'}class="active"{/if}><a href="{router page='page'}about/">{$aLang.page_about}</a></li>
		</ul>
		
		{if $oUserCurrent}
		<div class="profile">
			<a href="{$oUserCurrent->getUserWebPath()}" class="avatar"><img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="{$oUserCurrent->getLogin()}" /></a>
			<ul>
				<li><a href="{$oUserCurrent->getUserWebPath()}" class="author">{$oUserCurrent->getLogin()}</a> (<a href="{router page='login'}exit/">{$aLang.exit}</a>)</li>
				<li>
					{if $iUserCurrentCountTalkNew}
						<a href="{router page='talk'}" class="message" id="new_messages" title="{$aLang.user_privat_messages_new}">{$iUserCurrentCountTalkNew}</a> 
					{else}
						<a href="{router page='talk'}" class="message-empty" id="new_messages">&nbsp;</a>
					{/if}
					{$aLang.user_settings} <a href="{router page='settings'}profile/" class="author">{$aLang.user_settings_profile}</a> | <a href="{router page='settings'}tuning/" class="author">{$aLang.user_settings_tuning}</a> 
				</li>
				<li>{$aLang.user_rating} <strong>{$oUserCurrent->getRating()}</strong></li>
			</ul>
		</div>
		{else}
		<div class="profile guest">
			<a href="{router page='login'}" onclick="return showLoginForm();">{$aLang.user_login_submit}</a> {$aLang.or} 
			<a href="{router page='registration'}" class="reg">{$aLang.registration_submit}</a>
		</div>
		{/if}
		
		
	</div>
	<!-- /Header -->