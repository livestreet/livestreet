{**
 * Блок
 *
 * @param string       $title      Заголовок
 * @param string       $content
 * @param boolean      $show
 * @param array|string $list
 * @param array|string $tabs
 * @param string       $mods       Список модификторов основного блока (через пробел)
 * @param string       $classes    Список классов основного блока (через пробел)
 * @param array        $attributes Список атрибутов основного блока
 *}

{$component = 'ls-block'}
{component_define_params params=[ 'title', 'content', 'show', 'footer', 'list', 'tabs', 'mods', 'classes', 'attributes' ]}

{block 'block_options'}{/block}

{$show = $show|default:true}

{if $show}
    <div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
        {* Шапка *}
        {if $title}
            <header class="{$component}-header">
                {block 'block_header_inner'}
                    <h3 class="{$component}-title">
                        {$title}
                    </h3>
                {/block}
            </header>
        {/if}

        {block 'block_header_after'}{/block}

        {* Содержимое *}
        {if $content}
            {block 'block_content'}
                <div class="{$component}-content">
                    {block 'block_content_inner'}
                        {$content}
                    {/block}
                </div>
            {/block}
        {/if}

        {block 'block_content_after'}{/block}

        {* List group *}
        {if is_array( $list )}
            {component 'item' template='group' params=$list}
        {elseif $list}
            {$list}
        {/if}

        {* Tabs *}
        {if is_array( $tabs )}
            {component 'tabs' classes='js-tabs-block' params=$tabs}
        {elseif $tabs}
            {$tabs}
        {/if}

        {* Подвал *}
        {if $footer}
            {block 'block_footer'}
                <div class="{$component}-footer">
                    {block 'block_footer_inner'}
                        {$footer}
                    {/block}
                </div>
            {/block}
        {/if}

        {block 'block_footer_after'}{/block}
    </div>
{/if}