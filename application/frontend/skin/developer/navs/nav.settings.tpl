{**
 * Навигация на странице настроек
 *}

{include 'components/nav/nav.tpl'
		 sName       = 'settings'
		 sActiveItem = $sMenuSubItemSelect
		 sMods       = 'pills'
		 aItems = [
		   	[ 'url' => "{router page='settings'}profile/", 'text' => {lang name='user.settings.nav.profile'}, 'name' => 'profile' ],
		   	[ 'url' => "{router page='settings'}account/", 'text' => {lang name='user.settings.nav.account'}, 'name' => 'account' ],
		   	[ 'url' => "{router page='settings'}tuning/",  'text' => {lang name='user.settings.nav.tuning'},  'name' => 'tuning' ],
		   	[ 'url' => "{router page='settings'}invite/",  'text' => {lang name='user.settings.nav.invites'},  'name' => 'invite', 'is_enabled' => Config::Get('general.reg.invite') ]
		 ]}