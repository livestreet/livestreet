{include file='header.tpl'}

{include file='menu.blog.tpl'}


<BR>
<div class="blogposts">
	<div class="beach_party_britain">
   		<select style="font-size: 130%; color: rgb(51, 51, 51); width: 100%;" onChange="window.location.replace(DIR_WEB_ROOT+'/top/topic/' + this.value + '/');">
    		<option value="24h" {if $aParams[0] and $aParams[0]=='24h'}selected{/if}>Популярные, за последние 24 часа</option>
    		<option value="7d"  {if $aParams[0] and $aParams[0]=='7d'}selected{/if} >Популярные, за последние 7 дней</option>
    		<option value="30d" {if $aParams[0] and $aParams[0]=='30d'}selected{/if}>Популярные, за последние 30 дней</option>
    		<option value="all" {if $aParams[0] and $aParams[0]=='all'}selected{/if} >Популярные навсегда, за все время</option>
   		</select> 
	</div>
	
	
</div>

{include file='topic_list.tpl'}


{include file='footer.tpl'}

