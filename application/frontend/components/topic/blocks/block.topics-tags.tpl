{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends 'component@tags.block.tags'}

{block 'block_title'}
	{lang 'tags.block_tags.title'}
{/block}

{block 'block_options' append}
	{$classes = "{$classes} js-block-default"}
{/block}

{block 'block_content'}
	{component 'tags' template='cloud' tags=$smarty.local.tags     url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/' assign=tags_block_all}
	{component 'tags' template='cloud' tags=$smarty.local.tagsUser url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/' assign=tags_block_favourite}

	{component 'tabs' classes='js-tabs-block' tabs=[
        [ 'text' => {lang 'tags.block_tags.nav.all'},       'content' => $tags_block_all ],
        [ 'text' => {lang 'tags.block_tags.nav.favourite'}, 'content' => $tags_block_favourite, 'is_enabled' => !! $oUserCurrent ]
    ]}
{/block}

{* Подвал *}
{block 'block_footer'}
    {component 'tags' template='search-form' mods='light'}
{/block}