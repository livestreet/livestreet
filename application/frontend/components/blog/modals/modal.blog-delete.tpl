{**
 * Удаление блога
 *
 * @param object $blog
 * @param array  $blogs
 *}

{capture 'modal_content'}
    {$blog = $smarty.local.blog}

    <form action="{router page='blog'}delete/{$blog->getId()}/" method="POST" id="js-blog-remove-form">
        {* Скрытые поля *}
        {component 'field' template='hidden.security-key'}

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
    </form>
{/capture}

{component 'modal'
    title         = {lang 'blog.remove.title'}
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'blog-delete'
    id            = 'modal-blog-delete'
    primaryButton = [
        'text' => {lang 'common.remove'},
        'form' => 'js-blog-remove-form'
    ]}