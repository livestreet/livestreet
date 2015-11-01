{**
 * Список блогов
 *
 * @param array $blogs
 *}

{foreach $smarty.local.blogs as $blog}
    {component 'blog' template='list-item' blog=$blog}
{/foreach}