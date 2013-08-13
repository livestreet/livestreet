{**
 * Профиль пользователя с информацией о нем
 *
 * @styles css/profile.css
 * @styles css/tables.css
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_options' append}
	{$oSession = $oUserProfile->getSession()}
	{$oGeoTarget = $oUserProfile->getGeoTarget()}
{/block}

{block name='layout_content'}
	{include file='navs/nav.user.info.tpl'}

	{hook run='user_info_begin' oUserProfile=$oUserProfile}

	<div class="profile-info-about">
		<a href="{$oUserProfile->getUserWebPath()}" class="avatar">
			<img src="{$oUserProfile->getProfileAvatarPath(100)}" alt="avatar" itemprop="photo" />
		</a>

		<h3>{$aLang.profile_about}</h3>

		{if $oUserProfile->getProfileAbout()}	
			<p class="text">{$oUserProfile->getProfileAbout()}</p>
		{else}
			<p>{$aLang.profile_about_empty}</p>
		{/if}

		<br />

		{if $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId()}
			<a href="{router page='settings'}" class="edit">{$aLang.profile_about_edit}</a>
		{/if}
	</div>

	{hook run='user_info_about_after' oUserProfile=$oUserProfile}

	<div class="clearfix">
		<div class="profile-left">
			{$aUserFieldValues = $oUserProfile->getUserFieldValues(true,array(''))}

			{if $oUserProfile->getProfileSex() != 'other' || $oUserProfile->getProfileBirthday() || $oGeoTarget || $oUserProfile->getProfileAbout() || count($aUserFieldValues)}
				<h2 class="header-table">{$aLang.profile_privat}</h2>
				
				
				<ul class="dotted-list">		
					{if $oUserProfile->getProfileSex() != 'other'}
						<li class="dotted-list-item">
							<span class="dotted-list-item-label">{$aLang.profile_sex}</span>
							<strong class="dotted-list-item-value">
								{if $oUserProfile->getProfileSex() == 'man'}
									{$aLang.profile_sex_man}
								{else}
									{$aLang.profile_sex_woman}
								{/if}
							</strong>
						</li>
					{/if}
						
						
					{if $oUserProfile->getProfileBirthday()}
						<li class="dotted-list-item">
							<span class="dotted-list-item-label">{$aLang.profile_birthday}</span>
							<strong class="dotted-list-item-value">{date_format date=$oUserProfile->getProfileBirthday() format="j F Y" notz=true}</strong>
						</li>
					{/if}
					
					
					{if $oGeoTarget}
						<li class="dotted-list-item">
							<span class="dotted-list-item-label">{$aLang.profile_place}</span>
							<strong itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address" class="dotted-list-item-value">
								{if $oGeoTarget->getCountryId()}
									<a href="{router page='people'}country/{$oGeoTarget->getCountryId()}/" itemprop="country-name">{$oUserProfile->getProfileCountry()|escape:'html'}</a>{if $oGeoTarget->getCityId()},{/if}
								{/if}
								
								{if $oGeoTarget->getCityId()}
									<a href="{router page='people'}city/{$oGeoTarget->getCityId()}/" itemprop="locality">{$oUserProfile->getProfileCity()|escape:'html'}</a>
								{/if}
							</strong>
						</li>
					{/if}

					{if $aUserFieldValues}
						{foreach $aUserFieldValues as $oField}
							<li class="dotted-list-item">
								<span class="dotted-list-item-label"><i class="icon-contact icon-contact-{$oField->getName()}"></i> {$oField->getTitle()|escape:'html'}</span>
								<strong class="dotted-list-item-value">{$oField->getValue(true,true)}</strong>
							</li>
						{/foreach}
					{/if}

					{hook run='profile_whois_privat_item' oUserProfile=$oUserProfile}
				</ul>
			{/if}

			{hook run='profile_whois_item_after_privat' oUserProfile=$oUserProfile}



			<h2 class="header-table">{$aLang.profile_activity}</h2>

			<ul class="dotted-list">
				{if $oConfig->GetValue('general.reg.invite') and $oUserInviteFrom}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_invite_from}</span>
						<strong class="dotted-list-item-value">							       						
							<a href="{$oUserInviteFrom->getUserWebPath()}">{$oUserInviteFrom->getLogin()}</a>&nbsp;         					
						</strong>
					</li>
				{/if}
				
				
				{if $oConfig->GetValue('general.reg.invite') and $aUsersInvite}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_invite_to}</span>
						<strong class="dotted-list-item-value">
							{foreach $aUsersInvite as $oUserInvite}        						
								<a href="{$oUserInvite->getUserWebPath()}">{$oUserInvite->getLogin()}</a>&nbsp; 
							{/foreach}
						</strong>
					</li>
				{/if}
				
				
				{if $aBlogsOwner}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_blogs_self}</span>
						<strong class="dotted-list-item-value">							
							{foreach $aBlogsOwner as $oBlog}
								<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>{if ! $oBlog@last}, {/if}								      		
							{/foreach}
						</strong>
					</li>
				{/if}
				
				
				{if $aBlogAdministrators}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_blogs_administration}</span>
						<strong class="dotted-list-item-value">
							{foreach $aBlogAdministrators as $oBlogUser}
								{$oBlog = $oBlogUser->getBlog()}
								<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>{if ! $oBlogUser@last}, {/if}
							{/foreach}
						</strong>
					</li>
				{/if}
				
				
				{if $aBlogModerators}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_blogs_moderation}</span>
						<strong class="dotted-list-item-value">
							{foreach $aBlogModerators as $oBlogUser}
								{$oBlog = $oBlogUser->getBlog()}
								<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>{if ! $oBlogUser@last}, {/if}
							{/foreach}
						</strong>
					</li>
				{/if}
				
				
				{if $aBlogUsers}
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_blogs_join}</span>
						<strong class="dotted-list-item-value">
							{foreach $aBlogUsers as $oBlogUser}
								{$oBlog = $oBlogUser->getBlog()}
								<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>{if ! $oBlogUser@last}, {/if}
							{/foreach}
						</strong>
					</li>
				{/if}

				
				{hook run='profile_whois_activity_item' oUserProfile=$oUserProfile}
				
				
				<li class="dotted-list-item">
					<span class="dotted-list-item-label">{$aLang.profile_date_registration}</span>
					<strong class="dotted-list-item-value">{date_format date=$oUserProfile->getDateRegister()}</strong>
				</li>
				
				
				{if $oSession}				
					<li class="dotted-list-item">
						<span class="dotted-list-item-label">{$aLang.profile_date_last}</span>
						<strong class="dotted-list-item-value">{date_format date=$oSession->getDateLast()}</strong>
					</li>
				{/if}
			</ul>



			{if $aUsersFriend}
				<h2 class="header-table mb-15"><a href="{$oUserProfile->getUserWebPath()}friends/">{$aLang.profile_friends}</a> <span>{$iCountFriendsUser}</span></h2>
				
				{include file='user_list_avatar.tpl' aUsersList=$aUsersFriend}
			{/if}

			{hook run='profile_whois_item_end' oUserProfile=$oUserProfile}
		</div> 
		<!-- /profile-left -->
		
		
		
		<div class="profile-right">
			{$aUserFieldContactValues = $oUserProfile->getUserFieldValues(true,array('contact'))}

			{if $aUserFieldContactValues}
				<h2 class="header-table">{$aLang.profile_contacts}</h2>
				
				<ul class="profile-contact-list">
					{foreach $aUserFieldContactValues as $oField}
						<li><i class="icon-contact icon-contact-{$oField->getName()}" title="{$oField->getName()}"></i> {$oField->getValue(true,true)}</li>
					{/foreach}
				</ul>
			{/if}


			{$aUserFieldContactValues = $oUserProfile->getUserFieldValues(true,array('social'))}

			{if $aUserFieldContactValues}
				<h2 class="header-table">{$aLang.profile_social}</h2>
				
				<ul class="profile-contact-list">
					{foreach $aUserFieldContactValues as $oField}
						<li><i class="icon-contact icon-contact-{$oField->getName()}" title="{$oField->getName()}"></i> {$oField->getValue(true,true)}</li>
					{/foreach}
				</ul>
			{/if}
			
			{hook run='profile_whois_item' oUserProfile=$oUserProfile}
		</div>
	</div>

	{hook run='user_info_end' oUserProfile=$oUserProfile}
{/block}