{**
 * Список плагинов
 *
 * @param array $plugins Список плагинов
 *}

<table class="table admin-plugins">
    <tbody>
        {foreach $smarty.local.plugins as $plugin}
            <tr {if $plugin.is_active}class="active"{/if}>
                {* Название и описание плагина *}
                <td>
                    <h3>{$plugin.property->name->data}</h3>
                    <p>{$plugin.property->description->data}</p>

                    {component 'info-list' list=[
                        [ 'label' => {lang 'admin.plugins.plugin.version'}, 'content' => $plugin.property->version|escape ],
                        [ 'label' => {lang 'admin.plugins.plugin.author'},  'content' => $plugin.property->author->data ],
                        [ 'label' => {lang 'admin.plugins.plugin.url'},     'content' => $plugin.property->homepage ]
                    ]}
                </td>

                {* Действия *}
                <td>
                    <ul class="admin-plugins-actions">
                        {* Активировать/деактивировать *}
                        <li>
                            {if $plugin.is_active}
                                {component 'button'
                                    url  = "{router page='admin'}plugins/?plugin={$plugin.code}&action=deactivate&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                    text = {lang 'admin.plugins.plugin.deactivate'}}
                            {else}
                                {component 'button'
                                    url  = "{router page='admin'}plugins/?plugin={$plugin.code}&action=activate&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                    mods = 'primary'
                                    text = {lang 'admin.plugins.plugin.activate'}}
                            {/if}
                        </li>

                        {* Применить обновление *}
                        {if $plugin.apply_update && $plugin.is_active}
                            <li>
                                {component 'button'
                                    url  = "{router page='admin'}plugins/?plugin={$plugin.code}&action=apply_update&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                    text = {lang 'admin.plugins.plugin.apply_update'}}
                            </li>
                        {/if}

                        {* Ссылка на страницу настроек *}
                        {if $plugin.property->settings != "" && $plugin.is_active}
                            <li>
                                {component 'button'
                                    url  = $plugin.property->settings
                                    text = {lang 'admin.plugins.plugin.settings'}}
                            </li>
                        {/if}

                        {* Удалить *}
                        <li>
                            {component 'button'
                                url        = "{router page='admin'}plugins/?plugin={$plugin.code}&action=remove&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                                attributes = [ 'onclick' => "return confirm('{lang 'common.remove_confirm'}');" ]
                                text       = {lang 'admin.plugins.plugin.remove'}}
                        </li>
                    </ul>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>