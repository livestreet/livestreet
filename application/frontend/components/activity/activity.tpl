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
{$jsprefix = 'js-activity'}
{component_define_params params=[ 'events', 'count', 'targetId', 'mods', 'classes', 'attributes' ]}

{$moreCount = $count - count($events)}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {if $events}
        {* Список *}
        <ul class="activity-event-list {$jsprefix}-event-list">
            {component 'activity' template='event-list' events=$events}
        </ul>

        {* Кнопка подгрузки *}
        {if $count > Config::Get('module.stream.count_default')}
            {$last = end($events)}

            {component 'more'
                count      = $moreCount
                classes    = "{$jsprefix}-more"
                ajaxParams = [
                    'last_id' => $last->getId(),
                    'target_id' => $targetId
                ]}
        {/if}
    {else}
        {component 'blankslate' text=$aLang.common.empty}
    {/if}
</div>