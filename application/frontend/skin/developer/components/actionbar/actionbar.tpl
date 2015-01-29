{**
 * Экшнбар
 *
 * @param array  $items Массив с кнопками
 * @param string $mods
 * @param string $classes
 * @param array  $attributes
 *}

{extends 'Component@button.toolbar'}

{block 'button_toolbar_options' append}
    {$groups = $smarty.local.items}
    {$classes = "$classes actionbar"}
{/block}