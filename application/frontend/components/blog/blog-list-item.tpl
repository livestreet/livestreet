{**
 * Блог в списке блогов
 *
 * @param object $blog
 *}


{$blog = $smarty.local.blog}

{* Заголовок *}
{capture 'title'}
    {if $blog->getType() == 'close'}
        <i title="{$aLang.blog.private}" class="icon-lock"></i>
    {/if}

    <a href="{$blog->getUrlFull()}">{$blog->getTitle()|escape}</a>
{/capture}

{* Описание *}
{capture 'desc'}
    {$blog->getDescription()|strip_tags|truncate:120}
{/capture}

{* Описание *}
{capture 'content'}
    {* Действия *}
    <div class="blog-list-item-actions">
        {* Вступить/покинуть блог *}
        {include './join.tpl' blog=$blog}
    </div>

    {* Информация *}
    {$info = [
        [ 'label' => "{$aLang.blog.users.readers_total}:", 'content' => "<span class=\"js-blog-users-count\" data-blog-id=\"{$blog->getId()}\">{$blog->getCountUser()}</span>" ],
        [ 'label' => "{$aLang.blog.topics_total}:",        'content' => $blog->getCountTopic() ]
    ]}

    {if $blog->category->getCategory()}
        {$info[] = [ 'label' => "{$aLang.blog.categories.category}:", 'content' => $blog->category->getCategory()->getTitle() ]}
    {/if}

    {component 'info-list' list=$info classes='object-list-item-info'}
{/capture}

{component 'item'
    title=$smarty.capture.title
    desc=$smarty.capture.desc
    content=$smarty.capture.content
    image=[
        'url' => $blog->getUrlFull(),
        'path' => $blog->getAvatarPath( 100 ),
        'alt' => $blog->getTitle()|escape
    ]}