{**
 * Список событий активности
 *
 * @param array   $events
 * @param integer $targetId
 * @param integer $count
 *
 * @param string  $mods
 * @param string  $classes
 * @param string  $attributes
 *}

{$component = 'activity'}

{$events = $smarty.local.events}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
    {if $events}
        {* Список *}
        <ul class="activity-event-list js-activity-event-list">
            {include './event-list.tpl' events=$events}
        </ul>

        {* Кнопка подгрузки *}
        {if $smarty.local.count > Config::Get('module.stream.count_default')}
            {$last = end($events)}

            {include 'components/more/more.tpl'
                count      = $smarty.local.count
                classes    = "js-activity-more"
                attributes = [ 'data-proxy-last_id' => $last->getId(), 'data-param-target_id' => $smarty.local.targetId ]}
        {/if}
    {else}
        {include 'components/alert/alert.tpl' text=$aLang.common.empty mods='empty'}
    {/if}
</div>