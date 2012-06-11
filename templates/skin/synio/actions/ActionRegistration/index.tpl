{include file='header.tpl'}

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#registration-form').find('input.js-ajax-validate').blur(function(e){
			var aParams={ };
			if ($(e.target).attr('name')=='password_confirm') {
				aParams['password']=$('#registration-user-password').val();
			}
			if ($(e.target).attr('name')=='password') {
				aParams['password']=$('#registration-user-password').val();
				if ($('#registration-user-password-confirm').val()) {
					ls.user.validateRegistrationField('password_confirm',$('#registration-user-password-confirm').val(),$('#registration-form'),{ 'password': $(e.target).val() });
				}
			}
			ls.user.validateRegistrationField($(e.target).attr('name'),$(e.target).val(),$('#registration-form'),aParams);
		});
		$('#registration-form').bind('submit',function(){
			ls.user.registration('registration-form');
			return false;
		});
		$('#registration-form-submit').attr('disabled',false);
	});
</script>

<h2 class="page-header">{$aLang.registration}</h2>

{hook run='registration_begin'}

<form action="{router page='registration'}" method="post" id="registration-form" class="registration-form">
	<div class="wrapper-content">
		{hook run='form_registration_begin'}
		
		<dl class="form-item">
			<dt><label for="registration-login">{$aLang.registration_login}:</label></dt>
			<dd>
				<input type="text" name="login" id="registration-login" value="{$_aRequest.login}" class="input-text input-width-250 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-login"></small>
				
				<div class="form-item-help form-item-help-login">
					<i class="icon-ok-green validate-ok-field-login" style="display: none"></i>
					<i class="icon-question-sign js-tip-help" title="{$aLang.registration_login_notice}"></i>
				</div>
			</dd>
		</dl>
		
		<dl class="form-item">
			<dt><label for="registration-mail">{$aLang.registration_mail}:</label></dt>
			<dd>
				<input type="text" name="mail" id="registration-mail" value="{$_aRequest.mail}" class="input-text input-width-250 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-mail"></small>
				
				<div class="form-item-help form-item-help-mail">
					<i class="icon-ok-green validate-ok-field-mail" style="display: none"></i>
					<i class="icon-question-sign js-tip-help" title="{$aLang.registration_mail_notice}"></i>
				</div>
			</dd>
		</dl>
		
		<dl class="form-item">
			<dt><label for="registration-user-password">{$aLang.registration_password}:</label></dt>
			<dd>
				<input type="password" name="password" id="registration-user-password" value="" class="input-text input-width-250 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-password"></small>
				
				<div class="form-item-help form-item-help-password">
					<i class="icon-ok-green validate-ok-field-password" style="display: none"></i>
					<i class="icon-question-sign js-tip-help" title="{$aLang.registration_password_notice}"></i>
				</div>
			</dd>
		</dl>

		<dl class="form-item">
			<dt><label for="registration-user-password-confirm">{$aLang.registration_password_retry}:</label></dt>
			<dd>
				<input type="password" value="" id="registration-user-password-confirm" name="password_confirm" class="input-text input-width-250 js-ajax-validate" />
				<small class="validate-error-hide validate-error-field-password_confirm"></small>
				
				<div class="form-item-help form-item-help-password_confirm">
					<i class="icon-ok-green validate-ok-field-password_confirm" style="display: none"></i>
					<i class="icon-question-sign js-tip-help" title="{$aLang.registration_password_notice}"></i>
				</div>
			</dd>
		</dl>
	</div>
	
	<div class="wrapper-content wrapper-content-dark">
		{hookb run="registration_captcha"}
		<dl class="form-item">
			<dt><label for="registration-user-captcha">{$aLang.registration_captcha}:</label></dt>
			<dd>
				<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}" 
					 onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&n='+Math.random();" 
					 class="captcha-image" />
				<input type="text" name="captcha" id="registration-user-captcha" value="" maxlength="3" class="input-text input-width-100 js-ajax-validate" style="width: 165px" />
				<small class="validate-error-hide validate-error-field-captcha"></small>
				
				<div class="form-item-help form-item-help-captcha">
					<i class="icon-ok-green validate-ok-field-captcha" style="display: none"></i>
				</div>
			</dd>
		</dl>
		{/hookb}
		
		{hook run='form_registration_end'}
	</div>
	
	
	<div class="wrapper-content">
		<dl class="form-item">
			<dt></dt>
			<dd>
				<button type="submit" name="submit_register" class="button button-primary" id="registration-form-submit" disabled="disabled">{$aLang.registration_submit}</button>
			</dd>
		</dl>
		
	</div>
</form>

{hook run='registration_end'}

{include file='footer.tpl'}