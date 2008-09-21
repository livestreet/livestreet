{include file='header.tpl'}

{include file='system_message.tpl'}


{literal}
<script>
document.addEvent('domready', function() {	

	var inputUsers = $('talk_users');
 
	new Autocompleter.Request.HTML(inputUsers, DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php', {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 1, // We need at least 1 character
		'selectMode': 'type-ahead', // Instant completion
		'multiple': true // Tag support, by default comma separated
	});
});
</script>
{/literal}

<BR>
<table width="100%"  border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">
<div id="content" class="ppl">

		<div class="oldmenu">
   			<div class="oldmenuitem_2 {if $sEvent=='inbox'}active{/if}"><a href="{$DIR_WEB_ROOT}/talk/">Почтовый ящик</a></div>
			<div class="oldmenuitem_2 {if $sEvent=='add'}active{/if}"><a href="{$DIR_WEB_ROOT}/talk/add/">Написать</a></div>
		</div>
</div>


<div class="backoffice">

   
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">

         
      <p>
       <label for="talk_users">Кому:</label>
       <input type="text" id="talk_users" name="talk_users" value="{$_aRequest.talk_users}" style="width: 100%;" /><br />

       <span class="form_note">Список ваших собеседников, перечисляем через запятую. Пользователь подставляется по первым буквам</span><br />
       <span class="form_note_red"></span>
      </p>
     
      <p>
       <label for="talk_title">Заголовок:</label>
       <input type="text" id="talk_title" name="talk_title" value="{$_aRequest.talk_title}" style="width: 100%;" /><br />

       <span class="form_note">Заголовок должен быть наполнен смыслом, чтобы ваши адресаты поняли что к чему :)</span><br />
       <span class="form_note_red"></span>
      </p>
     
         <label for="talk_text">Текст:</label>           
		<textarea style="width: 100%;" name="talk_text" id="talk_text" rows="12">{$_aRequest.talk_text}</textarea><br>            
		<span class="form_note">Между прочим, можно использовать html-теги</span>
<br />

    <p class="l-bot">     
     <input type="submit" name="submit_talk_add" value="отправить">&nbsp;     
    </p>

    <div class="form_note">Если нажать кнопку &laquo;Отправить&raquo;, сообщение 
    незамедлительно отправится адресатам</div>    
	<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}">
    </form>
     </div>



</td>
</tr>
</table>



{include file='footer.tpl'}

