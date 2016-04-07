{**
 * Список пользователей блога
 *}

{component_define_params params=[ 'titleLang' ]}

{capture 'block_title'}
    {$usersCount = count($blogUsers)}

    {if $usersCount}
        {$usersCount} {lang "{$titleLang|default:'blog.readers_declension'}" count=$usersCount plural=true}
    {else}
        {$aLang.blog.users.empty}
    {/if}
{/capture}

{component 'block'
    mods     = 'blog-users'
    title    = $smarty.capture.block_title
    titleUrl = "{$blog->getUrlFull()}users/"
    content  = {component 'user' template='avatar-list' users=$blogUsers blankslateParams=[ 'mods' => 'no-background' ]}}