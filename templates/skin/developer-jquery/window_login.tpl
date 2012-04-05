{if !$oUserCurrent}
	<div class="modal modal-login" id="login_form">
		<header>
			<a href="#" class="close jqmClose"></a>
		</header>
		
		
		<script>
			jQuery(function($){
				$('div.tab-content:not(:first)').hide();
			
				$('ul.nav-tabs a').click(function(){
					$('div.tab-content').hide();
					$('ul.nav-tabs li').removeClass('active');
					$('div.tab-content' + this.hash).show();
					$(this).parent('li').addClass('active');
				});
			});
		</script>
		
		
		<ul class="nav nav-pills nav-tabs">
		<li class="active"><a href="#tab_content_login">{$aLang.user_authorization}</a></li>
			<li><a href="#tab_content_register">{$aLang.registration}</a></li>
			<li><a href="#tab_content_recover">{$aLang.password_reminder}</a></li>
		</ul>
		
		
		<div id="tab_content_login" class="tab-content">
			<form action="{router page='login'}" method="post">
				{hook run='form_login_popup_begin'}

				<p><label for="login">{$aLang.user_login}:</label>
				<input type="text" name="login" id="login" class="input-text input-width-300"></p>
				
				<p><label for="password">{$aLang.user_password}:</label>
				<input type="password" name="password" id="password" class="input-text input-width-300"></p>
				
				<p><label><input type="checkbox" name="remember" class="input-checkbox" checked> {$aLang.user_login_remember}</label></p>

				{hook run='form_login_popup_end'}

				<button name="submit_login" class="button button-primary">{$aLang.user_login_submit}</button>
			</form>
		</div>
		
		
		<div id="tab_content_register" class="tab-content">
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$('#popup-registration-form').find('input.js-ajax-validate').blur(function(e){
						var aParams={ };
						if ($(e.target).attr('name')=='password_confirm') {
							aParams['password']=$('#popup-registration-user-password').val();
						}
						if ($(e.target).attr('name')=='password') {
							aParams['password']=$('#popup-registration-user-password').val();
							if ($('#popup-registration-user-password-confirm').val()) {
								ls.user.validateRegistrationField('password_confirm',$('#popup-registration-user-password-confirm').val(),$('#popup-registration-form'),{ 'password': $(e.target).val() });
							}
						}
						ls.user.validateRegistrationField($(e.target).attr('name'),$(e.target).val(),$('#popup-registration-form'),aParams);
					});
					$('#popup-registration-form').bind('submit',function(){
						ls.user.registration('popup-registration-form');
						return false;
					});
					$('#popup-registration-form-submit').attr('disabled',false);
				});
			</script>


			<form action="{router page='registration'}" method="post" id="popup-registration-form">
				{hook run='form_registration_begin' isPopup=true}

				<p><label for="popup-registration-login">{$aLang.registration_login}</label>
				<input type="text" name="login" id="popup-registration-login" value="{$_aRequest.login}" class="input-text input-width-300 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-login"></small></p>

				<p><label for="popup-registration-mail">{$aLang.registration_mail}</label>
				<input type="text" name="mail" id="popup-registration-mail" value="{$_aRequest.mail}" class="input-text input-width-300 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-mail"></small></p>

				<p><label for="popup-registration-user-password">{$aLang.registration_password}</label>
				<input type="password" name="password" id="popup-registration-user-password" value="" class="input-text input-width-300 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-password"></small></p>

				<p><label for="popup-registration-user-password-confirm">{$aLang.registration_password_retry}</label>
				<input type="password" value="" id="popup-registration-user-password-confirm" name="password_confirm" class="input-text input-width-300 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-password_confirm"></small></p>

				<p><label for="popup-registration-captcha">{$aLang.registration_captcha}</label>
				<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}" 
					 onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n='+Math.random();"
					 class="mb-10" /><br />
				<input type="text" name="captcha" id="popup-registration-captcha" value="" maxlength="3" class="input-text input-width-100 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-captcha"></small></p>

				{hook run='form_registration_end' isPopup=true}

				<input type="hidden" name="return-path" value="{$PATH_WEB_CURRENT|escape:'html'}">
				<button name="submit_register" class="button button-primary" id="popup-registration-form-submit" disabled="disabled">{$aLang.registration_submit}</button>
			</form>
		</div>
		
		
		<div id="tab_content_recover" class="tab-content">
			<form action="{router page='login'}reminder/" method="POST">
				<p><label for="mail">{$aLang.password_reminder_email}</label>
				<input type="text" name="mail" id="name" class="input-text input-width-300" /></p>	

				<button name="submit_reminder" class="button button-primary">{$aLang.password_reminder_submit}</button>
			</form>
		</div>
	</div>
{/if}