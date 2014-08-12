{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	<a href="{router page='admin'}">{lang name='admin.title'}</a>
	<span>&raquo;</span>
	{lang name='admin.items.plugins'}
{/block}

{block 'layout_content'}
	<form action="{router page='admin'}plugins/" method="post" id="form_plugins_list">
		<table class="table table-plugins">
			<thead>
				<tr>
					<th class="cell-checkbox"><input type="checkbox" name="" onclick="ls.tools.checkAll('form_plugins_checkbox', this, true);" /></th>
					<th>{$aLang.admin.plugins.plugin_name}</th>
					<th>{$aLang.admin.plugins.plugin_version}</th>
					<th>{$aLang.admin.plugins.plugin_author}</th>
	                <th>{$aLang.admin.plugins.plugin_settings}</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				{foreach $aPlugins as $aPlugin}
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
									<a href="{$aPlugin.property->settings}">{$aLang.admin.plugins.plugin_settings}</a>
								{else}

								{/if}
							{/if}
						</td>
						<td align="center">
							{if $aPlugin.is_active}
								<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button">{$aLang.admin.plugins.plugin_deactivate}</a>
							{else}
								<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button button-primary">{$aLang.admin.plugins.plugin_activate}</a>
							{/if}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>

		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		<input type="submit"
			   name="submit_plugins_del"
			   value="{lang name='admin.plugins.submit_delete'}"
			   class="button"
			   onclick="return (jQuery('.form_plugins_checkbox:checked').length==0)?false:confirm('{$aLang.common.remove_confirm}');" />
	</form>
{/block}