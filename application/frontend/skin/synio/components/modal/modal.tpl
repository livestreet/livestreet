{**
 * Базовый шаблон модальных окон
 *}

{* Название компонента *}
{$component = 'ls-modal'}
{component_define_params params=[ 'title', 'content', 'tabs', 'body', 'id', 'options', 'showFooter', 'primaryButton', 'mods', 'classes', 'attributes' ]}

{* Дефолтные значения *}
{$showFooter = $showFooter|default:true}

{block 'modal_options'}{/block}

{* Модальное окно *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}
    id="{$id}"
    data-type="modal"
    {cattr prefix='data-lsmodal-' list=$options}>

    {* Шапка *}
    {block 'modal_title'}
        <header class="{$component}-header">
            {* Заголовок *}
            {if $title}
                <h3 class="{$component}-title">{$title}</h3>
            {/if}

            {* Кнопка закрытия *}
            <div class="{$component}-close" data-type="modal-close">
                {component 'syn-icon' icon='close'}
            </div>
        </header>
    {/block}

    {block 'modal_header_after'}{/block}

    {* Содержимое *}
    {block 'modal_body'}
        {if ! $tabs && ! $body}
            <div class="{$component}-body">
                {block 'modal_content'}{$content}{/block}
            </div>
        {/if}
    {/block}

    {* Tabs *}
    {( is_array( $tabs ) ) ? {component 'tabs' classes="{$component}-tabs js-{$component}-tabs" params=$tabs} : $tabs}

    {$body}

    {* Подвал *}
    {block 'modal_footer'}
        {if $showFooter}
            <div class="{$component}-footer">
                {block 'modal_footer_inner'}
                    {* Кнопка закрытия окна *}
                    {component 'button' type='button' text={lang 'common.cancel'} attributes=[ 'data-type' => 'modal-close' ]}

                    {* Кнопка отвечающее за основное действие *}
                    {( is_array( $primaryButton ) ) ? {component 'button' mods='primary' params=$primaryButton} : $primaryButton}
                {/block}
            </div>
        {/if}
    {/block}
</div>