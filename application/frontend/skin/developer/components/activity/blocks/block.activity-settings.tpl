{**
 * Блок настройки ленты активности
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {$aLang.activity.settings.title}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} activity-settings"}
{/block}

{block 'block_content'}
    {include 'components/activity/settings.tpl' typesActive=$typesActive types=$types}
{/block}