<TABLE class=pagemenu id=pagemenuszd>
	<TBODY>
	<TR>
    	<TD style="WIDTH: 12px; background-color: #ffffff;"></TD>
   		<TD style="WIDTH: 14px; background-color: #ffffff;"></TD>
    	<TD style="WIDTH: 10px; background-color: #ffffff;"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuSubItemSelect=='add'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/{if $sMenuItemSelect=='add_blog'}topic{else}{$sMenuItemSelect}{/if}/add/">Новые</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuSubItemSelect=='saved'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/topic/saved/">Черновики</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>   	
    	
    	{assign var="sel" value=""}
    	{if $sMenuSubItemSelect=='published'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/topic/published/">Опубликованные</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    </TR>
	</TBODY>
</TABLE>


{if $sMenuSubItemSelect=='add'}
<TABLE {if $sEvent=='published'}class="pagesubmenu toright" id="pagemenuszd2"{else}class="pagesubmenu" id="pagemenuszd"{/if}>
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $sMenuItemSelect=='topic'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuItemSelect=='topic'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/topic/{$sMenuSubItemSelect}/">Топик</A>
    </TD>   
    
    <TD class="subitem2 three_columns{if $sMenuItemSelect=='question'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuItemSelect=='question'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/question/{$sMenuSubItemSelect}/">Вопрос</A>
    </TD>
    
    <TD class="subitem2 three_columns{if $sMenuItemSelect=='link'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuItemSelect=='link'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/link/{$sMenuSubItemSelect}/">Ссылка</A>
    </TD>
    
    <TD class="subitem2 three_columns" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuItemSelect=='add_blog'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/blog/add/" style="color: #d00;">Создать блог</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}

