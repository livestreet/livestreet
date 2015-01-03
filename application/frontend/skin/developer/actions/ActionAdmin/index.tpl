{**
 * Админка
 *
 * @param boolean $availableAdminPlugin
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {lang 'admin.title'}
{/block}

{block 'layout_content'}
    {component 'nav'
        name  = 'admin'
        mods  = 'stacked pills'
        items = [
            [ 'name' => 'user', 'url' => "{router page='admin/plugins'}?plugin=admin&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => {lang 'admin.install_plugin_admin'}, is_enabled => $availableAdminPlugin ],
            [ 'name' => 'plugins',  'url' => "{router page='admin'}plugins/", 'text' => {lang 'admin.items.plugins'} ]
        ]}
{/block}