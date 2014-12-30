{**
 * Удаление блога
 *
 * @param object $blog
 * @param array  $blogs
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = "modal-blog-delete"}
    {$mods = "$mods blog-delete"}
    {$classes = "$classes js-modal-default"}
    {$title = $aLang.blog.remove.title}
{/block}

{block 'modal_content'}
    {$blog = $smarty.local.blog}

    <form action="{router page='blog'}delete/{$blog->getId()}/" method="POST" id="js-blog-remove-form">
        {* Переместить топики в блог *}
        {$selectBlogs = [
            [ 'value' => -1, 'text' => $aLang.blog.remove.remove_topics ]
        ]}

        {foreach $smarty.local.blogs as $blog}
            {$selectBlogs[] = [
                'value' => $blog->getId(),
                'text' => $blog->getTitle()|escape
            ]}
        {/foreach}

        {include 'components/field/field.select.tpl'
            name  = 'topic_move_to'
            label = $aLang.blog.remove.move_to
            items = $selectBlogs}

        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}
    </form>
{/block}

{block 'modal_footer_begin'}
    {include 'components/button/button.tpl' form='js-blog-remove-form' text=$aLang.common.remove mods='primary'}
{/block}