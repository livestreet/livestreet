{**
 * Блок настройки ленты активности
 *}

{extends 'Component@block.block'}

{block 'block_title'}
    {$aLang.activity.settings.title}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} activity-settings"}
{/block}

{block 'block_content'}
    {include '../settings.tpl' typesActive=$typesActive types=$types}
{/block}