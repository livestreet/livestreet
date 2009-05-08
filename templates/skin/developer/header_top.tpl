{if !$oUserCurrent}
	<div class="login-form">
		<a href="#" class="close" onclick="hideLoginForm(); return false;"></a>
		
		<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/" method="POST">
			<h3>{$aLang.user_authorization}</h3>	
			
			<p><label for="login">{$aLang.user_login}:</label>
			<input type="text" class="input-text" name="login" id="login-input"/></p>
			
			<p><label for="password">{$aLang.user_password}:</label>
			<input type="password" name="password" class="input-text" /></p>
			
			<p><label for="remember" class="checkbox-label"><input type="checkbox" name="remember" class="checkbox" checked />{$aLang.user_login_remember}</label></p>
			<input type="submit" name="submit_login" value="{$aLang.user_login_submit}" /><br /><br />
			
			<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/reminder/">{$aLang.user_password_reminder}</a><br />
			<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_REGISTRATION}/">{$aLang.user_registration}</a>
		</form>
	</div>
{/if}


<div id="header">
	<h1><a href="{$DIR_WEB_ROOT}">LiveStreet</a></h1>
	
	
	<ul class="nav-main">
		<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/">{$aLang.blogs}</a></li>
		<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/">{$aLang.people}</a></li>
		<li {if $sAction=='page' and $sEvent=='about'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/about/">{$aLang.page_about}</a></li>
	</ul>
	
	
	<ul class="profile">
		{if $oUserCurrent}
			<li><a href="{$oUserCurrent->getUserWebPath()}" class="avatar"><img src="{$oUserCurrent->getProfileAvatarPath(24)}" alt="{$oUserCurrent->getLogin()}" /></a></li>
			<li><a href="{$oUserCurrent->getUserWebPath()}" class="author">{$oUserCurrent->getLogin()}</a>&nbsp;/&nbsp;</li>
			<li>{$aLang.user_rating} <strong>{$oUserCurrent->getRating()}</strong>&nbsp;/&nbsp;</li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/add/" alt="{$aLang.topic_create}" title="{$aLang.topic_create}" class="submit-topic">Написать</a>&nbsp;/&nbsp;</li>
			<li>
				{if $iUserCurrentCountTalkNew}
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/" class="new-message" title="{$aLang.user_privat_messages_new}">{$aLang.user_privat_messages_alt} ({$iUserCurrentCountTalkNew})</a>&nbsp;/&nbsp;
				{else}
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.user_privat_messages} (0)</a>&nbsp;/&nbsp;
				{/if}
			</li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SETTINGS}/profile/">{$aLang.user_settings}</a>&nbsp;/&nbsp;</li>
			<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LOGIN}/exit/">{$aLang.exit}</a></li>
		{else}
			<li><a href="#" onclick="showLoginForm(); return false;">{$aLang.user_login_submit}</a>&nbsp;/&nbsp;</li>
			<li><a href="{$DIR_WEB_ROOT}/registration/" class="reg">{$aLang.registration_submit}</a></li>
		{/if}
	</ul>
</div>