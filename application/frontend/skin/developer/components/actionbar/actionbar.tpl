{**
 * Экшнбар
 *
 * @param array  $items Массив с кнопками
 * @param string $mods
 * @param string $classes
 * @param array  $attributes
 *}

{extends 'components/button/toolbar.tpl'}

{block 'button_toolbar_options' append}
    {$groups = $smarty.local.items}
    {$classes = "$classes actionbar"}
{/block}