{include file='header.tpl'}

{include file='menu.settings.tpl'}

{include file='system_message.tpl'}

<BR>
<table width="100%"  border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">



<p><span class="header">Изменение профиля</span>

<form action="{$DIR_WEB_ROOT}/settings/" method="POST" enctype="multipart/form-data">

	<label for="profile_name"><span class="form">Имя:</span></label><br />
	<input  style="width: 60%;" type="text" name="profile_name" tabindex="1" id="profile_name" value="{$oUserCurrent->getProfileName()|escape:'html'}" size="20" />	<br>
	<span class="form_note">Может состоять только из букв (A-Z a-z), цифр (0-9). Длина имени не может быть меньше 2 и больше 20 символов.</span><br />
	<br>

	<label for="mail"><span class="form">E-mail:</span></label><br />
	<input  style="width: 60%;" type="text" name="mail" tabindex="1" id="mail" value="{$oUserCurrent->getMail()|escape:'html'}" size="20" />	<br>
	<span class="form_note">Ваш реальный почтовый адрес, на него будут приходить уведомления</span><br />
	<br>

	<span class="form">Пол:</span><br />
	<input type="radio" name="profile_sex" id="profile_sex_m" value="man" {if $oUserCurrent->getProfileSex()=='man'}checked{/if}> &mdash; <span class="form"><label for="profile_sex_m">мужской</label></span> <br />
	<input type="radio" name="profile_sex" id="profile_sex_w" value="woman" {if $oUserCurrent->getProfileSex()=='woman'}checked{/if}> &mdash; <span class="form"><label for="profile_sex_w">женский</label></span><br />
	<input type="radio" name="profile_sex" id="profile_sex_o"  value="other" {if $oUserCurrent->getProfileSex()=='other'}checked{/if}> &mdash; <span class="form"><label for="profile_sex_o">не скажу</label></span><br />
	<br>

	

	
	
	<span class="form">Дата рождения: </span><br />
	<select name="profile_birthday_day">
		<option value="">день</option>
		{section name=date_day start=1 loop=32 step=1}    		
    		<option value="{$smarty.section.date_day.index}" {if $smarty.section.date_day.index==$oUserCurrent->getProfileBirthday()|date_format:"%d"}selected{/if}>{$smarty.section.date_day.index}</option>
		{/section}
	</select>
	<select name="profile_birthday_month">
		<option value="">месяц</option>		
		<option value="1" {if 1==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>января</option>
		<option value="2" {if 2==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>февраля</option>
		<option value="3" {if 3==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>марта</option>
		<option value="4" {if 4==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>апреля</option>
		<option value="5" {if 5==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>мая</option>
		<option value="6" {if 6==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>июня</option>
		<option value="7" {if 7==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>июля</option>
		<option value="8" {if 8==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>августа</option>
		<option value="9" {if 9==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>сентября</option>
		<option value="10" {if 10==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>октября</option>
		<option value="11" {if 11==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>ноября</option>
		<option value="12" {if 12==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>декабря</option>		
	</select>
	<select name="profile_birthday_year">
		<option value="">год</option>
		{section name=date_year start=1930 loop=2100 step=1}    		
    		<option value="{$smarty.section.date_year.index}" {if $smarty.section.date_year.index==$oUserCurrent->getProfileBirthday()|date_format:"%Y"}selected{/if}>{$smarty.section.date_year.index}</option>
		{/section}
	</select>
	<br><br>
	
	
	<span class="form">Страна: </span><br /><input  style="width: 60%;" type="text"	name="profile_country" value="{$oUserCurrent->getProfileCountry()|escape:'html'}"><br>
	<span class="form">Регион: </span><br /><input  style="width: 60%;" type="text"	name="profile_region" value="{$oUserCurrent->getProfileRegion()|escape:'html'}"><br>
	<span class="form">Город:  </span><br /><input  style="width: 60%;" type="text"	name="profile_city" value="{$oUserCurrent->getProfileCity()|escape:'html'}"><br>
	<br>

	
	<span class="form">ICQ:</span><br /> <input type="text"	name="profile_icq" value="{$oUserCurrent->getProfileIcq()|escape:'html'}"><br>
	<br>

	<span class="form">Сайт :</span><br /> <input style="width: 60%;" type="text"	id="profile_site" name="profile_site" value="{$oUserCurrent->getProfileSite()|escape:'html'}"> &mdash; <label for="profile_site"><span class="form">URL сайта</span></label><br>
	<input type="text" style="width: 60%;" id="profile_site_name"	name="profile_site_name" value="{$oUserCurrent->getProfileSiteName()|escape:'html'}"> &mdash; <label for="profile_site_name"><span class="form">название сайта </span></label><br>
	<br>

	
	<span class="form">О себе: </span><br /><textarea style="width: 60%;" name="profile_about">{$oUserCurrent->getProfileAbout()|escape:'html'}</textarea>
	<br>
	<br>
	<br>	
	<span class="form">Текущий пароль:</span><br /> <input type="password"	name="password_now" value=""><br>
	<span class="form">Новый пароль:</span><br /> <input type="password"	name="password" value=""><br>
	<span class="form">Еще раз новый пароль:</span><br /> <input type="password"	name="password_confirm" value=""><br><br>
<br>

{if $oUserCurrent->getProfileAvatar()}
		<img src="{$oUserCurrent->getProfileAvatarPath(100)}" border="0">
		<img src="{$oUserCurrent->getProfileAvatarPath(64)}" border="0">
		<img src="{$oUserCurrent->getProfileAvatarPath(24)}" border="0">
		<input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; <label for="avatar_delete"><span class="form">удалить</span></label><br /><br>
{/if}
	

<span class="form">Аватар:</span><br /> <input type="file" name="avatar" ><br>

	<p class="l-bot"><input type="submit" name="submit_profile_edit" tabindex="6" value="сохранить профиль" /></p>
</form>

<p><span class="txt_small">Может быть, перейти на <a href="{$DIR_WEB_ROOT}/">заглавную страницу</a>?</span><br />

</td>
</tr>
</table>


{include file='footer.tpl'}

