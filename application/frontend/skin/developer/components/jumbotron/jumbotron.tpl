{**
 * Jumbotron
 *
 * @param string $title
 * @param string $subtitle
 * @param string $titleUrl
 * @param string $content
 * @param string $mods
 * @param string $classes
 * @param array  $attributes
 *}

{* Название компонента *}
{$component = 'jumbotron'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'title', 'subtitle', 'titleUrl', 'content', 'mods', 'classes', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{block 'jumbotron_options'}{/block}

{* Jumbotron *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    <div class="{$component}-inner">
        {block 'jumbotron_inner'}
            {* Заголовок *}
            {if $title}
                <h1 class="{$component}-title">
                    {if $titleUrl}
                        <a href="{$titleUrl}">{$title}</a>
                    {else}
                        {$title}
                    {/if}
                </h1>
            {/if}

            {* Подзаголовок *}
            {if $subtitle}
                <h2 class="{$component}-subtitle">
                    {$subtitle}
                </h2>
            {/if}
        {/block}
    </div>
</div>