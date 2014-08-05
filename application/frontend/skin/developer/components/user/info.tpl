{hook run='user_info_begin' oUserProfile=$oUserProfile}

{**
 * Функции
 *}

{* Список пунктов *}
{function list}
	<h2 class="header-table">{$sTitle}</h2>
	<table class="table table-profile-info"><tbody>{$sContent}</tbody></table>
{/function}

{* Пункт списка *}
{function list_item}
	<tr>
		<td class="cell-label">{$sLabel}:</td>
		<td>{$sContent}</td>
	</tr>
{/function}


{**
 * О себе
 *}
{if $oUserProfile->getProfileAbout()}
	<div class="profile-info-about">
		<h3 class="h5">{$aLang.user.profile.about.title}</h3>

		<div class="text">
			{$oUserProfile->getProfileAbout()}
		</div>
	</div>
{/if}

{hook run='user_info_about_after' oUserProfile=$oUserProfile}


{**
 * Личное
 *}
{$aUserFieldValues = $oUserProfile->getUserFieldValues(true, array(''))}

{if $oUserProfile->getProfileSex() != 'other' || $oUserProfile->getProfileBirthday() || $oGeoTarget || $oUserProfile->getProfileAbout() || count($aUserFieldValues)}
	{capture 'info_private'}
		{* Пол *}
		{if $oUserProfile->getProfileSex() != 'other'}
			{list_item sLabel={lang name='user.profile.personal.gender'} sContent="{if $oUserProfile->getProfileSex() == 'man'}{lang name='user.profile.personal.gender_male'}{else}{lang name='user.profile.personal.gender_female'}{/if}"}
		{/if}

		{* День рождения *}
		{if $oUserProfile->getProfileBirthday()}
			{list_item sLabel={lang name='user.profile.personal.birthday'} sContent={date_format date=$oUserProfile->getProfileBirthday() format="j F Y" notz=true}}
		{/if}

		{* Местоположение *}
		{if $oGeoTarget}
			{capture 'info_private_geo'}
				<span itemprop="address" itemscope itemtype="http://data-vocabulary.org/Address">
					{if $oGeoTarget->getCountryId()}
						<a href="{router page='people'}country/{$oGeoTarget->getCountryId()}/" itemprop="country-name">{$oUserProfile->getProfileCountry()|escape}</a>{if $oGeoTarget->getCityId()},{/if}
					{/if}

					{if $oGeoTarget->getCityId()}
						<a href="{router page='people'}city/{$oGeoTarget->getCityId()}/" itemprop="locality">{$oUserProfile->getProfileCity()|escape}</a>
					{/if}
				</span>
			{/capture}

			{list_item sLabel={lang name='user.profile.personal.place'} sContent=$smarty.capture.info_private_geo}
		{/if}

		{* Контакты *}
		{if $aUserFieldValues}
			{foreach $aUserFieldValues as $oField}
				{list_item sLabel="{$oField->getTitle()|escape}" sContent=$oField->getValue(true, true)}
			{/foreach}
		{/if}

		{hook run='profile_whois_privat_item' oUserProfile=$oUserProfile}
	{/capture}

	{list sTitle={lang name='user.profile.personal.title'} sContent=$smarty.capture.info_private}
{/if}

{hook run='profile_whois_item_after_privat' oUserProfile=$oUserProfile}


{**
 * Контакты
 *}
{$aUserFieldContactValues = $oUserProfile->getUserFieldValues(true, array('contact'))}

{if $aUserFieldContactValues}
	{capture 'info_contacts'}
		{foreach $aUserFieldContactValues as $oField}
			{list_item sLabel="<i class=\"icon-contact icon-contact-{$oField->getName()}\"></i> {$oField->getTitle()|escape}" sContent=$oField->getValue(true, true)}
		{/foreach}
	{/capture}

	{list sTitle={lang name='user.profile.contact'} sContent=$smarty.capture.info_contacts}
{/if}


{**
 * Соц. сети
 *}
{$aUserFieldContactValues = $oUserProfile->getUserFieldValues(true, array('social'))}

{if $aUserFieldContactValues}
	{capture 'info_social'}
		{foreach $aUserFieldContactValues as $oField}
			{list_item sLabel="<i class=\"icon-contact icon-contact-{$oField->getName()}\"></i> {$oField->getTitle()|escape}" sContent=$oField->getValue(true, true)}
		{/foreach}
	{/capture}

	{list sTitle={lang name='user.profile.social_networks'} sContent=$smarty.capture.info_social}
{/if}

{hook run='profile_whois_item' oUserProfile=$oUserProfile}


{**
 * Активность
 *}
{capture 'info_activity'}
	{if Config::Get('general.reg.invite')}
		{* Кто пригласил пользователя *}
		{if $oUserInviteFrom}
			{list_item sLabel={lang name='user.profile.activity.invited_by'} sContent="<a href=\"{$oUserInviteFrom->getUserWebPath()}\">{$oUserInviteFrom->getDisplayName()}</a>"}
		{/if}

		{* Приглашенные пользователем *}
		{if $aUsersInvite}
			{foreach $aUsersInvite as $oUserInvite}
				{$sUsers = $sUsers|cat:"<a href=\"{$oUserInvite->getUserWebPath()}\">{$oUserInvite->getDisplayName()}</a>&nbsp;"}
			{/foreach}

			{list_item sLabel={lang name='user.profile.activity.invited'} sContent=$sUsers}
		{/if}
	{/if}

	{* Блоги созданные пользователем *}
	{if $aBlogsOwner}
		{foreach $aBlogsOwner as $oBlog}
			{$sBlogsOwner = $sBlogsOwner|cat:"<a href=\"{$oBlog->getUrlFull()}\">{$oBlog->getTitle()|escape}</a>{if ! $oBlog@last}, {/if}"}
		{/foreach}

		{list_item sLabel={lang name='user.profile.activity.blogs_created'} sContent=$sBlogsOwner}
	{/if}

	{* Блоги администрируемые пользователем *}
	{if $aBlogAdministrators}
		{foreach $aBlogAdministrators as $oBlogUser}
			{$oBlog = $oBlogUser->getBlog()}
			{$sBlogAdministrators = $sBlogAdministrators|cat:"<a href=\"{$oBlog->getUrlFull()}\">{$oBlog->getTitle()|escape}</a>{if ! $oBlogUser@last}, {/if}"}
		{/foreach}

		{list_item sLabel={lang name='user.profile.activity.blogs_admin'} sContent=$sBlogAdministrators}
	{/if}

	{* Блоги модерируемые пользователем *}
	{if $aBlogModerators}
		{foreach $aBlogModerators as $oBlogUser}
			{$oBlog = $oBlogUser->getBlog()}
			{$sBlogModerators = $sBlogModerators|cat:"<a href=\"{$oBlog->getUrlFull()}\">{$oBlog->getTitle()|escape}</a>{if ! $oBlogUser@last}, {/if}"}
		{/foreach}

		{list_item sLabel={lang name='user.profile.activity.blogs_mod'} sContent=$sBlogModerators}
	{/if}

	{* Блоги в которые вступил пользователь *}
	{if $aBlogUsers}
		{foreach $aBlogUsers as $oBlogUser}
			{$oBlog = $oBlogUser->getBlog()}
			{$sBlogUsers = $sBlogUsers|cat:"<a href=\"{$oBlog->getUrlFull()}\">{$oBlog->getTitle()|escape}</a>{if ! $oBlogUser@last}, {/if}"}
		{/foreach}

		{list_item sLabel={lang name='user.profile.activity.blogs_joined'} sContent=$sBlogUsers}
	{/if}

	{hook run='profile_whois_activity_item' oUserProfile=$oUserProfile}

	{* Дата регистрации *}
	{list_item sLabel={lang name='user.date_registration'} sContent={date_format date=$oUserProfile->getDateRegister()}}

	{* Дата последнего визита *}
	{if $oSession}
		{list_item sLabel={lang name='user.date_last_session'} sContent={date_format date=$oSession->getDateLast()}}
	{/if}
{/capture}

{list sTitle={lang name='user.profile.activity.title'} sContent=$smarty.capture.info_activity}


{**
 * Друзья
 *}
{if $aUsersFriend}
	<h2 class="header-table mb-15"><a href="{$oUserProfile->getUserWebPath()}friends/">{$aLang.user.friends.title}</a> ({$iCountFriendsUser})</h2>

	{include 'components/user_list_avatar/user_list_avatar.tpl' aUsersList=$aUsersFriend}
{/if}