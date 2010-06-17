{include file='header.tpl'}

<div class=topic>
	

<h2>{$aLang.page_admin}</h2>
<br>	
{if $aParams.0=='new'}
	<h4>{$aLang.page_create}</h4>
	{include file="$sTemplatePathPlugin/actions/ActionPage/add.tpl"}
{elseif $aParams.0=='edit'}
	<h4>{$aLang.page_edit} «{$oPageEdit->getTitle()}»</h4>
	{include file="$sTemplatePathPlugin/actions/ActionPage/add.tpl"}
{else}
	<a href="{router page='page'}admin/new/">{$aLang.page_new}</a>
{/if}



<table width="100%" cellspacing="0" class="admin_page">
  	<tr>
    	<th align="left">{$aLang.page_admin_title}</th>
    	<th align="center" width="250px">{$aLang.page_admin_url}</th>    	
    	<th align="center" width="50px">{$aLang.page_admin_active}</th>
    	<th align="center" width="70px">{$aLang.page_admin_main}</th>    	   	
    	<th align="center" width="80px">{$aLang.page_admin_action}</th>    	   	
  	</tr>
  	
 
  {foreach from=$aPages item=oPage name=el2}    
  {if $smarty.foreach.el2.iteration % 2  == 0}
  	{assign var=className value=''}
  {else}
  	{assign var=className value='colored'}
  {/if}
  <tr class="{$className}" onmouseover="this.className='colored_sel';" onmouseout="this.className='{$className}';">  
    <td align="left" valign="middle">
    	<img src="{$sTemplateWebPathPlugin}images/{if $oPage->getLevel()==0}folder{else}new{/if}_16x16.gif" alt="" title="" border="0" style="margin-left: {$oPage->getLevel()*20}px;"/>
    	<a href="{router page='page'}{$oPage->getUrlFull()}/">{$oPage->getTitle()}</a>
    </td>
    <td align="left">
    	/{$oPage->getUrlFull()}/
    </td>   
    <td align="center">
    {if $oPage->getActive()}
    	{$aLang.page_admin_active_yes}
    {else}
    	{$aLang.page_admin_active_no}
    {/if}
    </td>
    <td align="center">
		{if $oPage->getMain()}
			{$aLang.page_admin_active_yes}
		{else}
			{$aLang.page_admin_active_no}
		{/if}
	</td>
    <td align="center">  
    	<a href="{router page='page'}admin/edit/{$oPage->getId()}/"><img src="{$sTemplateWebPathPlugin}images/edit.gif" alt="{$aLang.page_admin_action_edit}" title="{$aLang.page_admin_action_edit}" border="0"/></a>      	
    	&nbsp;
      	<a href="{router page='page'}admin/delete/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('«{$oPage->getTitle()}»: {$aLang.page_admin_action_delete_confirm}');"><img src="{$sTemplateWebPathPlugin}images/delete.gif" alt="{$aLang.page_admin_action_delete}" title="{$aLang.page_admin_action_delete}" border="0"/></a>        	    
      	<a href="{router page='page'}admin/sort/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"><img src="{$sTemplateWebPathPlugin}images/up.png" alt="{$aLang.page_admin_sort_up}" title="{$aLang.page_admin_sort_up} ({$oPage->getSort()})" /></a>
		<a href="{router page='page'}admin/sort/{$oPage->getId()}/down/?security_ls_key={$LIVESTREET_SECURITY_KEY}"><img src="{$sTemplateWebPathPlugin}images/down.png" alt="{$aLang.page_admin_sort_down}" title="{$aLang.page_admin_sort_down} ({$oPage->getSort()})" /></a>					
    </td>   
  </tr>
  {/foreach}
  
</table>

	
</div>

{include file='footer.tpl'}