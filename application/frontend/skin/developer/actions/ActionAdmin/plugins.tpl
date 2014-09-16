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
		<table class="table table-plugins">
			<thead>
				<tr>
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

							{if $aPlugin.apply_update and $aPlugin.is_active}
								<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=apply_update&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button">{lang name='admin.plugins.plugin_apply_update'}</a>
							{/if}

							<a href="{router page='admin'}plugins/?plugin={$aPlugin.code}&action=remove&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button" onclick="return confirm('{$aLang.common.remove_confirm}');">{lang name='admin.plugins.plugin_delete'}</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
{/block}