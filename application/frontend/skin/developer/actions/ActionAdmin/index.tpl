{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}{lang name='admin.title'} {if $bAvailableAdminPlugin} - <a href="{router page='admin/plugins'}?plugin=admin&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}">{lang name='admin.install_plugin_admin'}</a>{/if}{/block}

{block 'layout_content'}
	<ul>
	    <li><a href="{router page="admin"}plugins/">{lang name='admin.items.plugins'}</a></li>
	    {hook run='admin_action_item'}
	</ul>

	{hook run='admin_action'}
{/block}