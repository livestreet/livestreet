{**
 * Список блогов
 *
 * @param array $blogs
 *}

{component_define_params params=[ 'blogs' ]}

{foreach $blogs as $blog}
    {component 'blog' template='list-item' blog=$blog}
{/foreach}