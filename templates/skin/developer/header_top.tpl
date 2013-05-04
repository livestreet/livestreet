<nav id="userbar" class="clearfix">
	{hook run='userbar_nav'}

	<ul class="nav nav-userbar">
		{if $oUserCurrent}
			<li class="nav-userbar-username">
				<a href="{$oUserCurrent->getUserWebPath()}" class="dropdown-toggle js-dropdown-default" data-type="dropdown-toggle" data-option-offset-y="2" data-option-target="js-dropdown-usermenu" onclick="return false">
					<img src="{$oUserCurrent->getProfileAvatarPath(24)}" alt="avatar" class="avatar" />
					{$oUserCurrent->getLogin()}
				</a>
			</li>
			<li><a href="{router page='topic'}add/" data-type="modal-toggle" data-option-target="modal-write">{$aLang.block_create}</a></li>

			{if $iUserCurrentCountTalkNew} 
				<li><a href="{router page='talk'}" class="new-messages" id="new_messages" title="{if $iUserCurrentCountTalkNew}{$aLang.user_privat_messages_new}{/if}">{$aLang.user_privat_messages} +{$iUserCurrentCountTalkNew}</a></li>
			{/if}

			{hook run='userbar_item'}
			<li><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
		{else}
			{hook run='userbar_item'}
			<li><a href="{router page='login'}" data-type="modal-toggle" data-option-target="modal-login" onclick="jQuery('[data-option-target=tab-pane-login]').tab('activate');">{$aLang.user_login_submit}</a></li>
			<li><a href="#" data-type="modal-toggle" data-option-target="modal-login" onclick="jQuery('[data-option-target=tab-pane-registration]').tab('activate');">{$aLang.registration_submit}</a></li>
		{/if}
	</ul>

	{if $oUserCurrent}
		{* User Menu *}

		<ul class="dropdown-menu" id="js-dropdown-usermenu">
			{* TODO: Add hooks *}
			{* TODO: Add counters *}

			<li {if $sAction=='profile' && ($aParams[0]=='whois' or $aParams[0]=='')}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}">{$aLang.user_menu_profile}</a></li>
			<li {if $sAction=='profile' && $aParams[0]=='wall'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}wall/">{$aLang.user_menu_profile_wall}</a></li>
			<li {if $sAction=='profile' && $aParams[0]=='created'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}created/topics/">{$aLang.user_menu_publication}</a></li>
			<li {if $sAction=='profile' && $aParams[0]=='favourites'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}favourites/topics/">{$aLang.user_menu_profile_favourites}</a></li>
			<li {if $sAction=='profile' && $aParams[0]=='friends'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}friends/">{$aLang.user_menu_profile_friends}</a></li>
			<li {if $sAction=='profile' && $aParams[0]=='stream'}class="active"{/if}><a href="{$oUserCurrent->getUserWebPath()}stream/">{$aLang.user_menu_profile_stream}</a></li>
			
			<li {if $sAction=='talk'}class="active"{/if}><a href="{router page='talk'}">{$aLang.talk_menu_inbox} {if $iUserCurrentCountTalkNew}<strong>+{$iUserCurrentCountTalkNew}</strong>{/if}</a></li>
			<li {if $sAction=='settings'}class="active"{/if}><a href="{router page='settings'}">{$aLang.settings_menu}</a></li>
		</ul>
	{/if}

	<form action="{router page='search'}topics/" class="search">
		<input type="text" placeholder="{$aLang.search}" maxlength="255" name="q" class="input-text">
		<input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search">
	</form>
</nav>


<header id="header" role="banner">
	{hook run='header_banner_begin'}
	<hgroup class="site-info">
		<h1 class="site-name"><a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a></h1>
		<h2 class="site-description">{cfg name='view.description'}</h2>
	</hgroup>
	{hook run='header_banner_end'}
</header>