{**
 * Tabs
 *
 * @param array $tabs Табы. Структура: [ 'text', 'content' ]
 *}

{$component = 'tabs'}

{$tabs = $smarty.local.tabs}

<div class="{$component} {$smarty.local.classes} {cmods name=$component mods=$smarty.local.mods}">
    {* Табы *}
    <ul class="tab-list clearfix" data-tab-type="tab-list">
        {foreach $tabs as $tab}
            {* Уникальный ID для привязки таба к его содержимому *}
            {$uid = "tab{rand( 0, 10e10 )}"}
            {$tabs[ $tab@index ][ 'uid' ] = $uid}

            {if $tab[ 'is_enabled' ]|default:true}
                <li class="tab {$tab[ 'classes' ]}
                    {if $tab@first}active{/if}"
                    data-tab-type="tab"
                    data-tab-target="{$uid}"
                    {if $tab[ 'url' ]}
                        data-tab-url="{$tab[ 'url' ]}"
                    {/if}
                    {$tab[ 'attributes' ]}>

                    {$tab[ 'text' ]}
                </li>
            {/if}
        {/foreach}
    </ul>

    {* Содержимое табов *}
    <div class="tabs-panes" data-type="tab-panes">
        {foreach $tabs as $tab}
            {if $tab[ 'is_enabled' ]|default:true}
                <div class="tab-pane" {if $tab@first}style="display: block"{/if} data-tab-type="tab-pane" id="{$tab[ 'uid' ]}">
                    {$tab[ 'content' ]}
                </div>
            {/if}
        {/foreach}
    </div>
</div>