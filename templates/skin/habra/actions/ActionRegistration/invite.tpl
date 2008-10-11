{include file='header.light.tpl'}

{include file='system_message.tpl'}

<table width="100%" border="0" cellspacing="4" cellpadding="4">
<tr>
<td align="left">

<p><span class="header">Регистрация по приглашению</span><br />
<form action="{$DIR_WEB_ROOT}/registration/invite/" method="POST">
<p><span class="form">Код приглашения: </span> <input type="text" value="" name="invite_code" size="29"> <input type="submit" name="submit_invite" value="проверить код"></p>
</form>

</td>
</tr>
</table>


{include file='footer.light.tpl'}