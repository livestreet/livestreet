{**
 * Заголовок страницы
 *
 * @param string  $text
 *}

{$component = 'page-header'}

<h2 class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
    {$smarty.local.text}
</h2>