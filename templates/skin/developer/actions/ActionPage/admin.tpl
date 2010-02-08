{include file='header.tpl'}

<h2>{$aLang.page_admin}</h2>
	
{if $aParams.0=='new'}
	<h3>{$aLang.page_create}</h3>
	{include file='actions/ActionPage/add.tpl'}
{elseif $aParams.0=='edit'}
	<h3>{$aLang.page_edit} «{$oPageEdit->getTitle()}»</h3>
	{include file='actions/ActionPage/add.tpl'}
{else}
	<a href="{router page='page'}admin/new/">{$aLang.page_new}</a>
{/if}

<br /><br />
<table class="people">
	<thead>
		<tr>
			<td class="user">{$aLang.page_admin_title}</td>
			<td width="150px">{$aLang.page_admin_url}</td>
			<td align="center" width="150px">{$aLang.page_admin_active}</td>
			<td align="center" width="150px">{$aLang.page_admin_action}</td>
		</tr>
	</thead>

	<tbody>
	{foreach from=$aPages item=oPage name=el2}
		<tr>  
			<td class="user without-image" style="padding-left: {if $oPage->getLevel()==0}10{else}{$oPage->getLevel()*20}{/if}px;">
				{if $oPage->getLevel()!=0}-&nbsp;{/if}<a href="{router page='page'}{$oPage->getUrlFull()}/">{$oPage->getTitle()}</a>
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
				<a href="{router page='page'}admin/edit/{$oPage->getId()}/">{$aLang.page_admin_action_edit}</a>      	
				&nbsp;
				<a href="{router page='page'}admin/delete/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('«{$oPage->getTitle()}»: {$aLang.page_admin_action_delete_confirm}');">{$aLang.page_admin_action_delete}</a>        	    
			</td>   
		</tr>
	{/foreach}
	</tbody>
</table>

{include file='footer.tpl'}