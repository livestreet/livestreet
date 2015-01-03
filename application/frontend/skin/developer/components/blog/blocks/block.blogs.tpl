{**
 * Блок со списком блогов
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
    {$mods = "{$mods} blogs nopadding"}
    {$classes = "{$classes} js-block-default"}
{/block}

{block 'block_title'}
    <a href="{router page='blogs'}">{lang 'blog.blocks.blogs.title'}</a>
{/block}

{block 'block_content'}
    {component 'tabs' classes='js-tabs-block' tabs=[
        [ 'text' => {lang 'blog.blocks.blogs.nav.top'},    'url' => "{router page='ajax'}blogs/top",  'content' => $sBlogsTop ],
        [ 'text' => {lang 'blog.blocks.blogs.nav.joined'}, 'url' => "{router page='ajax'}blogs/join", 'is_enabled' => !! $oUserCurrent ],
        [ 'text' => {lang 'blog.blocks.blogs.nav.self'},   'url' => "{router page='ajax'}blogs/self", 'is_enabled' => !! $oUserCurrent ]
    ]}
{/block}