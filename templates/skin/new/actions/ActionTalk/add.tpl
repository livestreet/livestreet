{include file='header.tpl' menu='talk'}


{literal}
<script language="JavaScript" type="text/javascript">
document.addEvent('domready', function() {	
	new Autocompleter.Request.HTML($('talk_users'), DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php', {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 1, // We need at least 1 character
		'selectMode': 'pick', // Instant completion
		'multiple': true // Tag support, by default comma separated
	});
});
</script>
{/literal}


			<div class="topic">
				<h1>{$aLang.talk_create}</h1>
				<form action="" method="POST" enctype="multipart/form-data">
					<p><label for="talk_users">{$aLang.talk_create_users}:</label><input type="text" class="w100p" id="talk_users" name="talk_users" value="{$_aRequest.talk_users}"/></p>
					<p><label for="talk_title">{$aLang.talk_create_title}:</label><input type="text" class="w100p" id="talk_title" name="talk_title" value="{$_aRequest.talk_title}"/></p>

					<p><div class="note"></div><label for="talk_text">{$aLang.talk_create_text}:</label>
					<textarea name="talk_text" id="talk_text" rows="12">{$_aRequest.talk_text}</textarea>
					</p><br />
					
					<p><input type="submit" value="{$aLang.talk_create_submit}" name="submit_talk_add"/></p>
				</form>
			</div>



{include file='footer.tpl'}