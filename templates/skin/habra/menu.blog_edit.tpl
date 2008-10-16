
<TABLE class=pagemenu id=pagemenuszd>
	<TBODY>
	<TR>
		{if $oUserCurrent}
    	<TD class="width10 read_"><IMG height=35 alt=" " src="{$DIR_STATIC_SKIN}/img/red_ul.gif" width=10></TD>
   		<TD class="subitem1 center read_" style="WIDTH: 14px">
   			<A href="{$DIR_WEB_ROOT}/topic/add/"><IMG title=написать height=14 alt="написать" src="{$DIR_STATIC_SKIN}/img/new_habratopic.gif" width=14></A>
   		</TD>
    	<TD class="border2px width10 read_"></TD>
    	{/if}	
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='profile'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/blog/edit/{$oBlogEdit->getId()}/">Профиль</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='admin'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/blog/admin/{$oBlogEdit->getId()}/">Пользователи</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>   	
    	
    </TR>
	</TBODY>
</TABLE>