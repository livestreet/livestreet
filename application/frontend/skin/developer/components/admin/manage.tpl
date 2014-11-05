{**
 * Админка
 *}

<ul>
    {if $bAvailableAdminPlugin}
        <li>
            <a href="{router page='admin/plugins'}?plugin=admin&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}">
                <strong>{lang 'admin.install_plugin_admin'}</strong>
            </a>
        </li>
    {/if}

    <li><a href="{router page="admin"}plugins/">{lang 'admin.items.plugins'}</a></li>

    {hook run='admin_action_item'}
</ul>

{hook run='admin_action'}