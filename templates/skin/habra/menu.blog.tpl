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
    	{if $sMenuItemSelect=='index'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}">Все</A> {if ($iCountTopicsNew)>0}<span style="color: #339900;">+{$iCountTopicsNew}</span>{/if}
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='blog'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/blog/">Коллективные</A> {if $iCountTopicsCollectiveNew}<span style="color: #339900;">+{$iCountTopicsCollectiveNew}</span>{/if}
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='log'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/log/">Персональные</A> {if $iCountTopicsPersonalNew}<span style="color: #339900;">+{$iCountTopicsPersonalNew}</span>{/if}
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sMenuItemSelect=='top'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/top/">TOP</A> 
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    </TR>
	</TBODY>
</TABLE>

{if $sMenuItemSelect=='index'}
<TABLE class="pagesubmenu" id="pagemenuszd">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='good'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='good'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$sMenuSubBlogUrl}/">Хорошие</A>
    </TD>   
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='new'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='new'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$sMenuSubBlogUrl}/new/">Новые</A> {if ($iCountTopicsNew)>0}<span style="color: #339900;">+{$iCountTopicsNew}</span>{/if}
    </TD>   
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}

{if $sMenuItemSelect=='blog'}
<TABLE class="pagesubmenu" id="pagemenuszd">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='good'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='good'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$sMenuSubBlogUrl}/">Хорошие</A>
    </TD>   
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='new'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='new'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$sMenuSubBlogUrl}/new/">Новые</A> {if $iCountTopicsBlogNew}<span style="color: #339900;">+{$iCountTopicsBlogNew}</span>{/if}
    </TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='bad'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='bad'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$sMenuSubBlogUrl}/bad/">Плохие</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}


{if $sMenuItemSelect=='log'}
<TABLE class="pagesubmenu toright" id="pagemenuszd2">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='good'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='good'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/log/">Хорошие</A>
    </TD>   
        
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='new'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='new'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/log/new/">Новые</A> {if $iCountTopicsPersonalNew}<span style="color: #339900;">+{$iCountTopicsPersonalNew}</span>{/if}
    </TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='bad'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='bad'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/log/bad/">Плохие</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}


{if $sMenuItemSelect=='top'}
<TABLE class="pagesubmenu toright" id="pagemenuszd2">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='blog'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='blog'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/top/blog/">Блоги</A>
    </TD>   
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='topic'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='topic'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/top/topic/">Топики</A>
    </TD>
    
    <TD class="subitem2 three_columns{if $sMenuSubItemSelect=='comment'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $sMenuSubItemSelect=='comment'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/top/comment/">Комментарии</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}