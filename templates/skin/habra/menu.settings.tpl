
<TABLE class=pagemenu id=pagemenuszd>
	<TBODY>
	<TR>
		{if $oUserCurrent}
    	<TD class="width10 read_"><IMG height=35 alt=" " src="{$DIR_STATIC_SKIN}/img/red_ul.gif" width=10></TD>
   		<TD class="subitem1 center read_" style="WIDTH: 14px">
   			<A href="{$DIR_WEB_ROOT}/topic/add/"><IMG title="{$aLang.topic_create}" height=14 alt="{$aLang.topic_create}" src="{$DIR_STATIC_SKIN}/img/new_habratopic.gif" width=14></A>
   		</TD>
    	<TD class="border2px width10 read_"></TD>
    	{/if}	
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='settings'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/settings/profile/">{$aLang.settings_menu}</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    {if $USER_USE_INVITE}	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='invite'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/settings/invite/">{$aLang.settings_menu_invite}</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>   	
    {/if}	
    </TR>
	</TBODY>
</TABLE>


{if $sMenuItemSelect=='settings'}
<TABLE class="pagesubmenu" id="pagemenuszd">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
              
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='profile'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='profile'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/settings/profile/">{$aLang.settings_menu_profile}</A>
    </TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='tuning'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='tuning'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/settings/tuning/">{$aLang.settings_menu_tuning}</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}