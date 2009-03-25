{include file='header.tpl' menu='talk'}


{literal}
<script>
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
				<h1>Новое письмо</h1>
				<form action="" method="POST" enctype="multipart/form-data">
					<p><label for="talk_users">Кому:</label><input type="text" class="w100p" id="talk_users" name="talk_users" value="{$_aRequest.talk_users}"/></p>
					<p><label for="talk_title">Заголовок:</label><input type="text" class="w100p" id="talk_title" name="talk_title" value="{$_aRequest.talk_title}"/></p>

					<p><div class="note"><a href="#">Доступны html-теги</a></div><label for="talk_text">Сообщение</label>
					<textarea name="talk_text" id="talk_text" rows="12">{$_aRequest.talk_text}</textarea>
					</p><br />
					
					<p><input type="submit" value="Отправить" name="submit_talk_add"/></p>
				</form>
			</div>



{include file='footer.tpl'}