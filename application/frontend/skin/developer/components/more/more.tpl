{**
 * Подгрузка контента
 *
 * @param string  $text
 * @param string  $target
 * @param integer $count
 * @param boolean $append
 * @param string  $mods
 * @param string  $classes
 * @param array   $attributes
 *}

{* Название компонента *}
{$component = 'more'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'text', 'target', 'count', 'append', 'mods', 'classes', 'attributes', 'ajaxParams' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{block 'more_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}"
    tabindex="0"
    {cattr list=$attributes}
    {cattr list=$ajaxParams prefix='data-param-'}
    {if $append}data-lsmore-append="{$append}"{/if}
    {if $target}data-lsmore-target="{$target}"{/if}>

    {* Текст *}
    {$text|default:{lang 'more.text'}}

    {* Счетчик *}
    {if $count}
        (<span class="js-more-count">{$count}</span>)
    {/if}
</div>