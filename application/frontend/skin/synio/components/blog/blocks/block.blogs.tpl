{**
 * Блок со списком блогов
 *}

{capture 'block_footer'}
    <a href="{router 'blogs'}">{lang 'synio.all_blogs'}</a>
{/capture}

{component 'block'
    mods     = 'blogs'
    classes  = 'blog-block-blogs js-block-default'
    title    = {lang 'blog.blocks.blogs.title'}
    titleUrl = {router page='blogs'}
    footer = $smarty.capture.block_footer
    tabs  = [
        'classes' => 'js-tabs-block',
        'tabs' => [
            [ 'text' => {lang 'blog.blocks.blogs.nav.top'},    'url' => "{router page='ajax'}blogs/top",  'list' => $sBlogsTop ],
            [ 'text' => {lang 'blog.blocks.blogs.nav.joined'}, 'url' => "{router page='ajax'}blogs/join", 'is_enabled' => !! $oUserCurrent ],
            [ 'text' => {lang 'blog.blocks.blogs.nav.self'},   'url' => "{router page='ajax'}blogs/self", 'is_enabled' => !! $oUserCurrent ]
        ]
    ]}