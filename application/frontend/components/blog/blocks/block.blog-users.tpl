{**
 * Список пользователей блога
 *}

{component_define_params params=[ 'titleLang' ]}

{capture 'block_title'}
    {$countBlogUsers} {lang "{$titleLang|default:'blog.readers_declension'}" count=$countBlogUsers plural=true}
{/capture}

{if $countBlogUsers}
    {component 'block'
        mods     = 'blog-users'
        title    = $smarty.capture.block_title
        titleUrl = "{$blog->getUrlFull()}users/"
        content  = {component 'user' template='avatar-list' users=$blogUsers blankslateParams=[ 'mods' => 'no-background' ]}}
{/if}