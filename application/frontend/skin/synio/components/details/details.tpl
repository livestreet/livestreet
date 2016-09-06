{**
 * Сворачиваемый блок
 *
 * @param string  $title
 * @param string  $content
 * @param string  $body
 * @param boolean $open
 * @param string  $mods
 * @param string  $classes
 * @param array   $attributes
 *}

{* Название компонента *}
{$component = 'ls-details'}
{$jsprefix = 'js-details'}
{component_define_params params=[ 'title', 'content', 'body', 'open', 'mods', 'classes', 'attributes' ]}

{* Проверяем нужно разворачивать блок или нет *}
{if $open}
    {$classes = "$classes is-open"}
{/if}

{block 'details_options'}{/block}

{* Item *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {* Заголовок *}
    <h3 class="{$component}-title {$jsprefix}-title">
        <span class="{$component}-title-text">{$title}</span>
    </h3>

    {* Основной блок *}
    <div class="{$component}-body {$jsprefix}-body">
        {* Содержимое *}
        {if $content}
            <div class="{$component}-content">
                {$content}
            </div>
        {/if}

        {$body}
    </div>
</div>