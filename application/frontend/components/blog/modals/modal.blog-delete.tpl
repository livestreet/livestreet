{**
 * Удаление блога
 *
 * @param object $blog
 * @param array  $blogs
 *}

{extends 'Component@modal.modal'}

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
            [ 'value' => -1, 'text' => "-- {$aLang.blog.remove.remove_topics} --" ]
        ]}

        {foreach $smarty.local.blogs as $blog}
            {$selectBlogs[] = [
                'value' => $blog->getId(),
                'text' => $blog->getTitle()|escape
            ]}
        {/foreach}

        {component 'field' template='select'
            name  = 'topic_move_to'
            label = $aLang.blog.remove.move_to
            items = $selectBlogs}

        {* Скрытые поля *}
        {component 'field' template='hidden.security-key'}
    </form>
{/block}

{block 'modal_footer_begin'}
    {component 'button' form='js-blog-remove-form' text=$aLang.common.remove mods='primary'}
{/block}