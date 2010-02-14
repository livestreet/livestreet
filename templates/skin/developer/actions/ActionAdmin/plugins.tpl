{include file='header.tpl' showWhiteBack=true}

<form action="{router page='admin'}plugins/" method="post" id="form_plugins_list">
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	<table class="people">
		<thead>
			<tr>
				<td width="20px"><input type="checkbox" name="" onclick="checkAllPlugins(this);"></td>
				<td class="name">{$aLang.plugins_plugin_name}</td>
				<td align="center">{$aLang.plugins_plugin_version}</td>
				<td>{$aLang.plugins_plugin_author}</td>														
				<td>{$aLang.plugins_plugin_action}</td>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aPlugins item=aPlugin}
			<tr>
				<td><input type="checkbox" name="plugin_del[{$aPlugin.code}]" class="form_plugins_checkbox"></td>
				<td><strong style="color: #333;">{$aPlugin.property->name->data|escape:'html'}</strong><br />{$aPlugin.property->description->data|escape:'html'}<br />{$aPlugin.property->homepage}</td>
				<td align="center">{$aPlugin.property->version|escape:'html'}</td>
				<td>{$aPlugin.property->author->data|escape:'html'}</td>							
				<td class="{if $aPlugin.is_active}deactivate{else}activate{/if}"><strong>{if $aPlugin.is_active}<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=deactivate">{$aLang.plugins_plugin_deactivate}</a>{else}<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=activate">{$aLang.plugins_plugin_activate}</a>{/if}</strong></td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	<input type="submit" name="submit_plugins_del" value="{$aLang.plugins_submit_delete}" onclick="return ($$('.form_plugins_checkbox:checked').length==0)?false:confirm('{$aLang.plugins_delete_confirm}');">				
</form>

{include file='footer.tpl'}