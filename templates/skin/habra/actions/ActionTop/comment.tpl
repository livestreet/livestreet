{include file='header.tpl'}

{include file='menu.blog.tpl'}


<BR>
<div class="blogposts">
	<div class="beach_party_britain">
   		<select style="font-size: 130%; color: rgb(51, 51, 51); width: 100%;" onChange="window.location.replace(DIR_WEB_ROOT+'/top/comment/' + this.value + '/');">
    		<option value="24h" {if $aParams[0] and $aParams[0]=='24h'}selected{/if}>{$aLang.blog_menu_top_period_24h}</option>
    		<option value="7d"  {if $aParams[0] and $aParams[0]=='7d'}selected{/if} >{$aLang.blog_menu_top_period_7d}</option>
    		<option value="30d" {if $aParams[0] and $aParams[0]=='30d'}selected{/if}>{$aLang.blog_menu_top_period_30d}</option>
    		<option value="all" {if $aParams[0] and $aParams[0]=='all'}selected{/if} >{$aLang.blog_menu_top_period_all}</option>
   		</select> 
	</div>
	
	
</div>

{include file='comment_list.tpl'}

{include file='footer.tpl'}

