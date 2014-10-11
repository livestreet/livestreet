{hook run='user_info_begin' user=$oUserProfile}


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
{$items = []}
{$userfields = $oUserProfile->getUserFieldValues(true, array(''))}

{* Пол *}
{if $oUserProfile->getProfileSex() != 'other'}
	{$items[] = [
		'label'   => {lang name='user.profile.personal.gender'},
		'content' => "{if $oUserProfile->getProfileSex() == 'man'}{lang name='user.profile.personal.gender_male'}{else}{lang name='user.profile.personal.gender_female'}{/if}"
	]}
{/if}

{* День рождения *}
{if $oUserProfile->getProfileBirthday()}
	{$items[] = [
		'label'   => {lang name='user.profile.personal.birthday'},
		'content' => {date_format date=$oUserProfile->getProfileBirthday() format="j F Y" notz=true}
	]}
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

	{$items[] = [
		'label'   => {lang name='user.profile.personal.place'},
		'content' => $smarty.capture.info_private_geo
	]}
{/if}

{include 'components/user/info-group.tpl' title={lang name='user.profile.personal.title'} items=$items}


{**
 * Контакты
 *}
{$items = []}
{$userfields = $oUserProfile->getUserFieldValues(true, array('contact'))}

{foreach $userfields as $field}
	{$items[] = [
		'label'   => "<i class=\"icon-contact icon-contact-{$field->getName()}\"></i> {$field->getTitle()|escape}",
		'content' => $field->getValue(true, true)
	]}
{/foreach}

{include 'components/user/info-group.tpl' name='contact' title={lang name='user.profile.contact'} items=$items}


{**
 * Соц. сети
 *}
{$items = []}
{$userfields = $oUserProfile->getUserFieldValues(true, array('social'))}

{foreach $userfields as $field}
	{$items[] = [
		'label'   => "<i class=\"icon-contact icon-contact-{$field->getName()}\"></i> {$field->getTitle()|escape}",
		'content' => $field->getValue(true, true)
	]}
{/foreach}

{include 'components/user/info-group.tpl' name='social-networks' title={lang name='user.profile.social_networks'} items=$items}



{**
 * Активность
 *}
{$items = []}

{if Config::Get('general.reg.invite')}
	{* Кто пригласил пользователя *}
	{if $oUserInviteFrom}
		{$items[] = [
			'label'   => {lang name='user.profile.activity.invited_by'},
			'content' => "<a href=\"{$oUserInviteFrom->getUserWebPath()}\">{$oUserInviteFrom->getDisplayName()}</a>"
		]}
	{/if}

	{* Приглашенные пользователем *}
	{if $aUsersInvite}
		{$users = ''}

		{foreach $aUsersInvite as $user}
			{$users = $users|cat:"<a href=\"{$user->getUserWebPath()}\">{$user->getDisplayName()}</a>&nbsp;"}
		{/foreach}

		{$items[] = [
			'label'   => {lang name='user.profile.activity.invited'},
			'content' => $users
		]}
	{/if}
{/if}

{* Блоги созданные пользователем *}
{if $aBlogsOwner}
	{$blogs = ''}

	{foreach $aBlogsOwner as $blog}
		{$blogs = $blogs|cat:"<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>{if ! $blog@last}, {/if}"}
	{/foreach}

	{$items[] = [
		'label'   => {lang name='user.profile.activity.blogs_created'},
		'content' => $blogs
	]}
{/if}

{* Блоги администрируемые пользователем *}
{if $aBlogAdministrators}
	{$blogs = ''}

	{foreach $aBlogAdministrators as $user}
		{$blog = $user->getBlog()}
		{$blogs = $blogs|cat:"<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>{if ! $user@last}, {/if}"}
	{/foreach}

	{$items[] = [
		'label'   => {lang name='user.profile.activity.blogs_admin'},
		'content' => $blogs
	]}
{/if}

{* Блоги модерируемые пользователем *}
{if $aBlogModerators}
	{$blogs = ''}

	{foreach $aBlogModerators as $user}
		{$blog = $user->getBlog()}
		{$blogs = $blogs|cat:"<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>{if ! $user@last}, {/if}"}
	{/foreach}

	{$items[] = [
		'label'   => {lang name='user.profile.activity.blogs_mod'},
		'content' => $blogs
	]}
{/if}

{* Блоги в которые вступил пользователь *}
{if $aBlogUsers}
	{$blogs = ''}

	{foreach $aBlogUsers as $user}
		{$blog = $user->getBlog()}
		{$blogs = $blogs|cat:"<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>{if ! $user@last}, {/if}"}
	{/foreach}

	{$items[] = [
		'label'   => {lang name='user.profile.activity.blogs_joined'},
		'content' => $blogs
	]}
{/if}

{* Дата регистрации *}
{$items[] = [
	'label'   => {lang name='user.date_registration'},
	'content' => {date_format date=$oUserProfile->getDateRegister()}
]}

{* Дата последнего визита *}
{if $oSession}
	{$items[] = [
		'label'   => {lang name='user.date_last_session'},
		'content' => {date_format date=$oSession->getDateLast()}
	]}
{/if}

{include 'components/user/info-group.tpl' name='activity' title={lang name='user.profile.activity.title'} items=$items}

{**
 * Друзья
 *}
{if $aUsersFriend}
	{capture 'user_info_friends'}
		{include 'components/user/user-list-avatar.tpl' aUsersList=$aUsersFriend}
	{/capture}

	{include 'components/user/info-group.tpl'
		title = "<a href=\"{$oUserProfile->getUserWebPath()}friends/\">{$aLang.user.friends.title}</a> ({$iCountFriendsUser})"
		html  = $smarty.capture.user_info_friends}
{/if}


{**
 * Стена
 *}
{capture 'user_info_wall'}
	{insert name='block' block='wall' params=[
		'classes' => 'js-wall-default',
		'user_id' => $oUserProfile->getId()
	]}
{/capture}

{include 'components/user/info-group.tpl' name='wall' title={lang name='wall.title'} html=$smarty.capture.user_info_wall}


{hook run='user_info_end' user=$oUserProfile}
