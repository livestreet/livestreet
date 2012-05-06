{assign var="noSidebar" value=true}
{include file='header.tpl'}


<h2 class="page-header"><a href="{router page='admin'}">{$aLang.admin_header}</a> <span>&raquo;</span> {$aLang.admin_list_plugins}</h2>


<form action="{router page='admin'}plugins/" method="post" id="form_plugins_list">
	<table class="table table-plugins">
		<thead>
			<tr>
				<th class="cell-checkbox"><input type="checkbox" name="" onclick="ls.tools.checkAll('form_plugins_checkbox', this, true);" /></th>
				<th>{$aLang.plugins_plugin_name}</th>
				<th>{$aLang.plugins_plugin_version}</th>
				<th>{$aLang.plugins_plugin_author}</th>
                <th>{$aLang.plugins_plugin_settings}</th>
				<th></th>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aPlugins item=aPlugin}
				<tr {if $aPlugin.is_active}class="active"{/if}>
					<td class="cell-checkbox"><input type="checkbox" name="plugin_del[{$aPlugin.code}]" class="form_plugins_checkbox" /></td>
					<td>
						<h3>{$aPlugin.property->name->data}</h3>
						{$aPlugin.property->description->data}
					</td>
					<td>{$aPlugin.property->version|escape:'html'}</td>
					<td>
						{$aPlugin.property->author->data}<br />
						{$aPlugin.property->homepage}
					</td>				
                    <td>
						{if $aPlugin.property->settings != ""}
							{if $aPlugin.is_active}
								<a href="{$aPlugin.property->settings}">{$aLang.plugins_plugin_settings}</a>
							{else}

							{/if}
						{/if}
					</td>
					<td>
						{if $aPlugin.is_active}
							<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.plugins_plugin_deactivate}</a>
						{else}
							<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{$aLang.plugins_plugin_activate}</a>
						{/if}
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	<input type="submit" 
		   name="submit_plugins_del" 
		   value="{$aLang.plugins_submit_delete}" 
		   class="button" 
		   onclick="return (jQuery('.form_plugins_checkbox:checked').length==0)?false:confirm('{$aLang.plugins_delete_confirm}');" />
</form>
				

{include file='footer.tpl'}