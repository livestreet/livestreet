{include file='header.tpl'}


</div>
</div>

<div id="content" class="ppl">
	<div id="col1">
		<p>Обратите внимание, эти люди находятся на сайте, или находились некоторое время назад &darr;</p>
		{foreach from=$aUsersLast item=oUserLast}
		<div class="ppl_user">
    		<a href="{$DIR_WEB_ROOT}/profile/{$oUserLast->getLogin()}/" title="посмотреть профиль" class="ppl_userpic"><img  class="img_border" src="{$oUserLast->getProfileAvatarPath(24)}" width="24" height="24" alt="" title="{$oUserLast->getLogin()}" border="0"></a>
    		<a href="{$DIR_WEB_ROOT}/profile/{$oUserLast->getLogin()}/" class="ppl_nickname">{$oUserLast->getLogin()}{if $oUserLast->getProfileName()} ({$oUserLast->getProfileName()|escape:'html'}){/if}</a>
    		<div class="time">
    			{date_format date=$oUserLast->getDateLast()}
    		</div>
   		</div>
   		{/foreach}		
   </div>
  
	<div id="col2">
		<div class="oldmenu">
   			<div class="oldmenuitem_2 {if $sEvent=='good'}active{/if}"><a href="{$DIR_WEB_ROOT}/people/good/">Хорошие</a></div>
			<div class="oldmenuitem_2 {if $sEvent=='bad'}active{/if}"><a href="{$DIR_WEB_ROOT}/people/bad/">Плохие</a></div>
		</div>
		<div class="habrablock">
			
	<div class="ppl_list">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="usertable">
    {if $aUsersRating}
     <tr>
     	<td width="5%" align="left" class="people_page_tb_header"></td>
        <td width="12%" align="left" class="people_page_tb_header"></td>
      	<td width="" align="left" class="people_page_tb_header"></td>
      	<td width="12%" align="center" class="people_page_tb_header">Сила</td>
      	<td width="24%" align="center" class="people_page_tb_header">Рейтинг</td>
     </tr>    
	{foreach from=$aUsersRating item=oUser}
     <tr>
	    <td align="center"> </td>
        <td align="center"><a href="{$DIR_WEB_ROOT}/profile/{$oUser->getLogin()}/"><img  class="img_border" src="{$oUser->getProfileAvatarPath(24)}" width="23" height="23" alt="" title="{$oUser->getLogin()}" border="0"></a></td>
		<td align="left"><a href="{$DIR_WEB_ROOT}/profile/{$oUser->getLogin()}/" class="people_nickname">{$oUser->getLogin()}</a></td>
      	<td align="center" nowrap class="people_karma" style="color: #25a8ff;">{$oUser->getSkill()}</td>
      	<td nowrap align="center"><span class="people_rating">{$oUser->getRating()}</span></td>
     </tr>
	{/foreach}
	{else}
	<tr>
	    <td align="center" colspan="5"><br>нет таких</td>        
     </tr>
	{/if}
	</table>
<br>

				
    			<div class="navcentered">
    				{include file='paging.tpl' aPaging=`$aPaging`}
				</div>
				   
			</div>

			
		</div>
	</div>

	<div id="col3"> 
		<h2>Статистика</h2>
		
		<div class="statsblock">
			<h4 class="people_stat_header"><img src="{$DIR_STATIC_SKIN}/img/people_arrow_2.gif">Сколько нас?</h4>
   			<ul>
    			<li>Всего пользователей: {$aStat.count_all}</li>
    			<li>Активные: {$aStat.count_active}</li>
    			<li>Заблудившиеся: {$aStat.count_inactive}</li>
  			</ul>
  		</div>

		<div class="statsblock">
			<h4 class="people_stat_header"><img src="{$DIR_STATIC_SKIN}/img/people_arrow_2.gif">Кто мы?</h4>
   			<ul>
   	 			<li>Мужчины: {$aStat.count_sex_man}</li>
    			<li>Женщины: {$aStat.count_sex_woman}</li>
    			<li>Пол не указан: {$aStat.count_sex_other}</li>
   			</ul>
		</div>

		<div style="clear: left;">
			&nbsp;
		</div>

		<div class="statsblock">
   			<h4 class="people_stat_header"><img src="{$DIR_STATIC_SKIN}/img/people_arrow_2.gif">Откуда мы?</h4>
   			<ul>
   				{foreach from=$aStat.count_country item=aValue key=sCountry}
        			<li>{$sCountry|escape:'html'}: {$aValue.count}</li>
        		{/foreach}
       		</ul>
		</div>

		<div class="statsblock">
			<h4 class="people_stat_header"><img src="{$DIR_STATIC_SKIN}/img/people_arrow_2.gif">Где мы?</h4>
			<ul>
        		{foreach from=$aStat.count_city item=aValue key=sCity}
        			<li>{$sCity|escape:'html'}: {$aValue.count}</li>
        		{/foreach}
       		</ul>
		</div>
	</div>
</div>

<div id="prefooter">
	<h2 class="people_sections">Новые люди</h2>  
	<div class="newpeople">
		{foreach from=$aUsersRegister item=oUser}
    		<div class="zurfuck"><a href="{$DIR_WEB_ROOT}/profile/{$oUser->getLogin()}/"><img src="{$DIR_STATIC_SKIN}/img/user.gif" width="10" height="10" border="0" alt="посмотреть профиль" title="посмотреть профиль"></a>&nbsp;<a href="{$DIR_WEB_ROOT}/profile/{$oUser->getLogin()}/" class="sf_page_nickname">{$oUser->getLogin()}</a><span class="people_date">{date_format date=$oUser->getDateRegister()}</span></div>
    	{/foreach}
   </div>
</div>


{include file='footer.tpl'}

