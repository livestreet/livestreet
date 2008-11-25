<div class="userinfo_karma_section_3">

	<table border="0">
		<tr valign="top">
			<td width="35px"></td>
			<td align="center" valign="top">
			<span class="userinfo_karma_text">{$aLang.user_rating}</span><br>			
			<span class="userinfo_karma">
				<nobr>
					
				
				
						<span id="user_vote_self_{$oUserProfile->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.user_vote_up}" title="{$aLang.user_vote_self}" />
     						<span id="user_rating_self_{$oUserProfile->getId()}" style="color: {if $oUserProfile->getRating()<0}#d00000{else}#008000{/if};">{$oUserProfile->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.user_vote_down}" title="{$aLang.user_vote_self}" />
     					</span>
     					<span id="user_vote_anonim_{$oUserProfile->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.user_vote_up}" title="{$aLang.user_vote_guest}" />
     						<span id="user_rating_anonim_{$oUserProfile->getId()}" style="color: {if $oUserProfile->getRating()<0}#d00000{else}#008000{/if};">{$oUserProfile->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.user_vote_down}" title="{$aLang.user_vote_guest}" />
     					</span>
     					<span id="user_vote_is_vote_down_{$oUserProfile->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="{$aLang.user_vote_up}" title="{$aLang.user_vote_already}" />
     						<span id="user_rating_is_vote_down_{$oUserProfile->getId()}" style="color: {if $oUserProfile->getRating()<0}#d00000{else}#008000{/if};">{$oUserProfile->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="{$aLang.user_vote_down}" title="{$aLang.user_vote_already}" />
     					</span>
     					<span id="user_vote_is_vote_up_{$oUserProfile->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="{$aLang.user_vote_up}" title="{$aLang.user_vote_already}" />
     						<span id="user_rating_is_vote_up_{$oUserProfile->getId()}" style="color: {if $oUserProfile->getRating()<0}#d00000{else}#008000{/if};">{$oUserProfile->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="{$aLang.user_vote_down}" title="{$aLang.user_vote_already}" />
     					</span>
     					<span id="user_vote_ok_{$oUserProfile->getId()}" style="display: none;" >
     						<a href="#" onclick="ajaxVoteUser({$oUserProfile->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="{$aLang.user_vote_up}" title="{$aLang.user_vote_up}" /></a>
     						<span id="user_rating_ok_{$oUserProfile->getId()}" style="color: {if $oUserProfile->getRating()<0}#d00000{else}#008000{/if};">{$oUserProfile->getRating()}</span>
     						<a href="#" onclick="ajaxVoteUser({$oUserProfile->getId()},-1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="{$aLang.user_vote_down}" title="{$aLang.user_vote_down}" /></a>
     					</span>
     					
     					{if $oUserCurrent}
     						{if $oUserProfile->getId()==$oUserCurrent->getId()}
   								<script>showUserVote('user_vote_self',{$oUserProfile->getId()});</script>
   							{else}
   								{if $oUserProfile->getUserIsVote()}
   									{if $oUserProfile->getUserVoteDelta()>0}
   										<script>showUserVote('user_vote_is_vote_up',{$oUserProfile->getId()});</script>
   									{else}
   										<script>showUserVote('user_vote_is_vote_down',{$oUserProfile->getId()});</script>
   									{/if}
   								{else}
   									<script>showUserVote('user_vote_ok',{$oUserProfile->getId()});</script>
   								{/if}
   							{/if}     						
     					{else}
     						<script>showUserVote('user_vote_anonim',{$oUserProfile->getId()});</script>
     					{/if}
				
							
				</nobr>			
			</span>		
			<div class="userinfo_karma_text"><span id="user_count_vote_{$oUserProfile->getId()}">{$oUserProfile->getCountVote()}</span> {$aLang.user_vote_count}<br /></div>			
			</td>
			<td width="50px"></td>
			<td align="center" valign="top">
			<span class="userinfo_karma_text">{$aLang.user_skill}</span><br>
			<span class="userinfo_karma">
				<span id="user_skill_{$oUserProfile->getId()}" style="color: #ffffff; background-color: #25a8ff;">{$oUserProfile->getSkill()}</span>
			</span>
			</td>
		</tr>
	</table>

	<br>		
</div>
<TABLE class=pagemenu id=pagemenuszd>
	<TBODY>
	<TR>
		{if $oUserCurrent}
    	<TD class="width10 read_"><IMG height=35 alt=" " src="{$DIR_STATIC_SKIN}/img/red_ul.gif" width=10></TD>
   		<TD class="subitem1 center read_" style="WIDTH: 14px">
   			<A href="{$DIR_WEB_ROOT}/topic/add/"><IMG title={$aLang.topic_create} height=14 alt="{$aLang.topic_create}" src="{$DIR_STATIC_SKIN}/img/new_habratopic.gif" width=14></A>
   		</TD>
    	<TD class="border2px width10 read_"></TD>
    	{/if}	
    	
    	{assign var="sel" value=""}
    	{if $sAction=='profile'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/profile/{$oUserProfile->getLogin()}/">{$aLang.user_menu_profile}</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>
    	
    	{assign var="sel" value=""}
    	{if $sAction=='my'}
    		{assign var="sel" value="sel "}
    	{/if}
    	<TD class="{$sel}width10"></TD>
    	<TD class="{$sel}subitem1 center">
    		<A class=tags_f href="{$DIR_WEB_ROOT}/my/{$oUserProfile->getLogin()}/">{$aLang.user_menu_publication}{if ($iCountCommentUser+$iCountTopicUser)>0} ({$iCountCommentUser+$iCountTopicUser}){/if}</A>
    	</TD>
    	<TD class="{$sel}border2px width10"></TD>   	
    	
    </TR>
	</TBODY>
</TABLE>


{if $sAction=='profile'}
<TABLE class="pagesubmenu" id="pagemenuszd">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $aParams[0]=='whois' or $aParams[0]==''} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $aParams[0]=='whois' or $aParams[0]==''}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/profile/{$oUserProfile->getLogin()}/">{$aLang.user_menu_profile_whois}</A>
    </TD>   
    
    <TD class="subitem2 three_columns{if $aParams[0]=='favourites'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $aParams[0]=='favourites'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/profile/{$oUserProfile->getLogin()}/favourites/">{$aLang.user_menu_profile_favourites}{if $iCountTopicFavourite} ({$iCountTopicFavourite}){/if}</A>
    </TD>
    
    <TD class="subitem2 three_columns{if $aParams[0]=='tags'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $aParams[0]=='tags'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/profile/{$oUserProfile->getLogin()}/tags/">{$aLang.user_menu_profile_tags}</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}

{if $sAction=='my'}
<TABLE class="pagesubmenu toright" id="pagemenuszd2">
  <TBODY>
  <TR>
    <TD vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_bl.gif" width=10></TD>
    
    <TD class="subitem2 three_columns{if $aParams[0]=='blog' or $aParams[0]==''} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $aParams[0]=='blog' or $aParams[0]==''}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/my/{$oUserProfile->getLogin()}/">{$aLang.user_menu_publication_blog}{if $iCountTopicUser} ({$iCountTopicUser}){/if}</A> <a href="{$DIR_WEB_ROOT}/rss/log/{$oUserProfile->getLogin()}/" title="{$aLang.user_menu_publication_comment_rss}"><IMG  height=12 src="{$DIR_STATIC_SKIN}/img/rss_small.gif" width=12></a>
    </TD>   
    
    <TD class="subitem2 three_columns{if $aParams[0]=='comment'} active_personal{/if}" noWrap align=middle>
    	<IMG class=arrow_cc height=7 src="{$DIR_STATIC_SKIN}/img/{if $aParams[0]=='comment'}arrow_menu_main.gif{else}arrow_menu_main_un.gif{/if}" width=10><A href="{$DIR_WEB_ROOT}/my/{$oUserProfile->getLogin()}/comment/">{$aLang.user_menu_publication_comment}{if $iCountCommentUser} ({$iCountCommentUser}){/if}</A>
    </TD>
    
    <TD style="BORDER-RIGHT: white 2px solid" vAlign=bottom width=10><IMG height=10 src="{$DIR_STATIC_SKIN}/img/green2_br.gif" width=10></TD>
  </TR>
  </TBODY>
</TABLE>
{/if}