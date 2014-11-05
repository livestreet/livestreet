{**
 * Удаление блога
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-blog-delete{/block}
{block 'modal_class'}modal-blog-delete js-modal-default{/block}
{block 'modal_title'}{$aLang.blog.remove.title}{/block}

{block 'modal_content'}
    <form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST" id="js-blog-remove-form">
        {* Переместить топики в блог *}
        {$aBlogsCustom = [
            [ 'value' => -1, 'text' => $aLang.blog.remove.remove_topics ]
        ]}

        {foreach $aBlogs as $oBlog}
            {$aBlogsCustom[] = [
                'value' => $oBlog->getId(),
                'text' => $oBlog->getTitle()|escape
            ]}
        {/foreach}

        {include 'components/field/field.select.tpl'
                 name  = 'topic_move_to'
                 label = $aLang.blog.remove.move_to
                 items = $aBlogsCustom}


        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}
    </form>
{/block}

{block 'modal_footer_begin'}
    {include 'components/button/button.tpl' form='js-blog-remove-form' text=$aLang.common.remove mods='primary'}
{/block}