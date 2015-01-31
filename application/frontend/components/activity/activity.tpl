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

<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
    {if $events}
        {* Список *}
        <ul class="activity-event-list js-activity-event-list">
            {include './event-list.tpl' events=$events}
        </ul>

        {* Кнопка подгрузки *}
        {if $smarty.local.count > Config::Get('module.stream.count_default')}
            {$last = end($events)}

            {component 'more'
                count      = $smarty.local.count
                classes    = 'js-activity-more'
                ajaxParams = [
                    'last_id' => $last->getId(),
                    'target_id' => $smarty.local.targetId
                ]}
        {/if}
    {else}
        {component 'alert' text=$aLang.common.empty mods='empty'}
    {/if}
</div>