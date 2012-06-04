{assign var="noSidebar" value=true}
{include file='header.tpl'}

<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#reminder-form').bind('submit',function(){
			ls.user.reminder('reminder-form');
			return false;
		});
		$('#reminder-form-submit').attr('disabled',false);
	});
</script>

<h2 class="page-header">{$aLang.password_reminder}</h2>

<form action="{router page='login'}reminder/" method="POST" id="reminder-form">
	<p><label for="reminder-mail">{$aLang.password_reminder_email}</label>
	<input type="text" name="mail" id="reminder-mail" class="input-text input-width-200" />
	<small class="validate-error-hide validate-error-reminder"></small></p>

	<button type="submit"  name="submit_reminder" class="button button-primary" id="reminder-form-submit" disabled="disabled">{$aLang.password_reminder_submit}</button>
</form>



{include file='footer.tpl'}