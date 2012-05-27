{if $oUserCurrent}
<script>
	jQuery(document).ready(function($){
		// Dropdown
		var dp 		= $('#dropdown-user');
		var trigger = $('#dropdown-user-trigger');
		var menu 	= $('#dropdown-user-menu');
		var pos 	= $('#dropdown-user').offset();

		menu.appendTo('body').css({ 'left': pos.left, 'top': $('#dropdown-user').height() - 1, 'min-width': $('#dropdown-user').outerWidth(), 'display': 'none' });
		
		trigger.click(function(){
			menu.slideToggle();
			dp.toggleClass('opened');
			return false;
		});
		
		menu.find('a').click(function(){
			dp.removeClass('opened');
			trigger.find('a').text( $(this).text() );
			menu.slideToggle();
		});
		
		// Hide menu
		$(document).click(function(){
			dp.removeClass('opened');
			menu.slideUp();
		});
		
		$('body').on('click', '#dropdown-user-trigger, #dropdown-user-menu', function(e) {
			e.stopPropagation();
		});
		
		$(window).resize(function(){
			menu.css({ 'left': $('#dropdown-user').offset().left });
		});
	});
</script>
{/if}

<div id="header-wrapper">
	<header id="header" role="banner">
		{hook run='header_banner_begin'}
		<h1 class="site-name"><a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a></h1>
		
		
		<ul class="nav nav-main">
			<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a> <i></i></li>

			{hook run='main_menu_item'}
		</ul>
		
		{hook run='main_menu'}
		
		
		{hook run='userbar_nav'}
		
		{if $oUserCurrent}
			<div class="dropdown-user" id="dropdown-user">
				<a href="{$oUserCurrent->getUserWebPath()}"><img src="{$oUserCurrent->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
				<a href="{$oUserCurrent->getUserWebPath()}" class="username">{$oUserCurrent->getLogin()}</a>
				
				<div class="dropdown-user-trigger" id="dropdown-user-trigger"><i></i></div>
				
				<ul class="dropdown-user-menu" id="dropdown-user-menu" style="display: none">
					<li class="item-stat">
						<span class="rating"><i class="icon-synio-star-green"></i> {$oUserCurrent->getRating()} {*<i class="arrow icon-synio-arrow-up"></i>*}</span>
						<span class="strength"><i class="icon-synio-strength"></i> {$oUserCurrent->getSkill()} {*<i class="arrow icon-synio-arrow-down"></i>*}</span>
					</li>
					<li class="item-messages">
						<a href="{router page='talk'}" id="new_messages">
							<i class="item-icon"></i>
							{$aLang.user_privat_messages}
							{if $iUserCurrentCountTalkNew}<div class="new">+{$iUserCurrentCountTalkNew}</div>{/if}
						</a>
					</li>
					<li class="item-profile"><i class="item-icon"></i><a href="{$oUserCurrent->getUserWebPath()}">Мой профиль</a></li> {*r*}
					<li class="item-settings"><i class="item-icon"></i><a href="{router page='settings'}profile/">{$aLang.user_settings}</a></li>
					<li class="item-create"><i class="item-icon"></i><a href="{router page='topic'}add/">{$aLang.block_create}</a></li>
					{hook run='userbar_item'}
					<li class="item-signout"><i class="item-icon"></i><a href="{router page='login'}exit/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.exit}</a></li>
				</ul>
			</div>
		{else}
			<ul class="auth">
				{hook run='userbar_item'}
				<li><a href="{router page='registration'}" class="js-registration-form-show">{$aLang.registration_submit}</a></li>
				<li><a href="{router page='login'}" class="js-login-form-show sign-in">{$aLang.user_login_submit}</a></li>
			</ul>
		{/if}
		
		{if $iUserCurrentCountTalkNew}<a href="{router page='talk'}" class="new-messages">+{$iUserCurrentCountTalkNew} <i class="icon-synio-new-message"></i></a>{/if}
		
		
		{hook run='header_banner_end'}
	</header>
</div>