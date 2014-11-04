{extends 'components/item/item.tpl'}

{block 'options' append}
    {* Заголовок *}
    {capture 'blog_list_item_title'}
        {if $blog->getType() == 'close'}
            <i title="{$aLang.blog.private}" class="icon-lock"></i>
        {/if}

        <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
    {/capture}

    {$title = $smarty.capture.blog_list_item_title}

    {* Описание *}
    {capture 'blog_list_item_content'}
        <p class="object-list-item-description">{$blog->getDescription()|strip_tags|truncate:120}</p>

        {* Информация *}
        {$info = [
            [ 'label' => "{$aLang.blog.users.readers_total}:", 'content' => $blog->getCountUser() ],
            [ 'label' => "{$aLang.vote.rating}:",              'content' => $blog->getRating() ],
            [ 'label' => "{$aLang.blog.topics_total}:",        'content' => $blog->getCountTopic() ]
        ]}

        {if $blog->category->getCategory()}
            {$info[] = [ 'label' => "{$aLang.blog.categories.category}:", 'content' => $blog->category->getCategory()->getTitle() ]}
        {/if}

        {include 'components/info-list/info-list.tpl' list=$info classes='object-list-item-info'}

        {* Действия *}
        <div class="object-list-item-actions">
            {* Вступить/покинуть блог *}
            {include './join.tpl' blog=$blog}
        </div>
    {/capture}

    {$content = $smarty.capture.blog_list_item_content}

    {* Изображение *}
    {$image = [
        'url' => $blog->getUrlFull(),
        'path' => $blog->getAvatarPath( 100 ),
        'alt' => $blog->getTitle()|escape
    ]}
{/block}