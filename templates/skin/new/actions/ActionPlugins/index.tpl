{include file='header.tpl' showWhiteBack=true}

<div class="page people top-blogs plugins">	
				<table>
					<thead>
						<tr>
							<td class="name">{$aLang.plugins_plugin_name}</td>
							<td class="version">{$aLang.plugins_plugin_version}</td>
							<td class="author">{$aLang.plugins_plugin_author}</td>														
							<td class="action">{$aLang.plugins_plugin_action}</td>
						</tr>
					</thead>
					
					<tbody>
						{foreach from=$aPlugins item=aPlugin}
						<tr>
							<td class="name"><a class="title">{$aPlugin.code}</a></td>
							<td class="version">-</td>
							<td class="author">-</td>													
							<td class="{if $aPlugin.is_active}deactivate{else}activate{/if}"><strong>{if $aPlugin.is_active}<a href="{router page='plugins'}?plugin={$aPlugin.code}&action=deactivate">{$aLang.plugins_plugin_deactivate}</a>{else}<a href="{router page='plugins'}?plugin={$aPlugin.code}&action=activate">{$aLang.plugins_plugin_activate}</a>{/if}</strong></td>
						</tr>
						{/foreach}
					</tbody>
				</table>
</div>

{include file='footer.tpl'}