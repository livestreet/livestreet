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
        {foreach $smarty.local.plugins as $plugin}
            <tr {if $plugin.is_active}class="active"{/if}>
                <td>
                    <h3>{$plugin.property->name->data}</h3>
                    {$plugin.property->description->data}
                </td>
                <td>
                    {$plugin.property->version|escape}
                </td>
                <td>
                    {$plugin.property->author->data}<br />
                    {$plugin.property->homepage}
                </td>
                <td>
                    {if $plugin.property->settings != "" && $plugin.is_active}
                        <a href="{$plugin.property->settings}">{$aLang.admin.plugins.plugin_settings}</a>
                    {/if}
                </td>
                <td align="center">
                    {if $plugin.is_active}
                        <a href="{router page='admin'}plugins/?plugin={$plugin.code}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button">{$aLang.admin.plugins.plugin_deactivate}</a>
                    {else}
                        <a href="{router page='admin'}plugins/?plugin={$plugin.code}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button button-primary">{$aLang.admin.plugins.plugin_activate}</a>
                    {/if}

                    {if $plugin.apply_update and $plugin.is_active}
                        <a href="{router page='admin'}plugins/?plugin={$plugin.code}&action=apply_update&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button">{lang name='admin.plugins.plugin_apply_update'}</a>
                    {/if}

                    <a href="{router page='admin'}plugins/?plugin={$plugin.code}&action=remove&security_ls_key={$LIVESTREET_SECURITY_KEY}" class="button" onclick="return confirm('{$aLang.common.remove_confirm}');">{lang name='admin.plugins.plugin_delete'}</a>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>