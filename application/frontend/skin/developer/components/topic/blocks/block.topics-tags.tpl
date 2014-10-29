{**
 * Теги
 *
 * @styles css/blocks.css
 *}

{extends 'components/tags/blocks/block.tags.tpl'}

{block 'block_title'}
	{lang 'tags.block_tags.title'}
{/block}

{block 'block_options' append}
	{$classes = "{$classes} js-block-default"}
{/block}

{block 'block_content'}
	{include 'components/tags/tag-cloud.tpl' tags=$smarty.local.tags     url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/' assign=tags_block_all}
	{include 'components/tags/tag-cloud.tpl' tags=$smarty.local.tagsUser url='{router page=\'tag\'}{$tag->getText()|escape:\'url\'}/' assign=tags_block_favourite}

	{include 'components/tabs/tabs.tpl' classes='js-tabs-block' tabs=[
        [ 'text' => {lang 'tags.block_tags.nav.all'},       'content' => $tags_block_all ],
        [ 'text' => {lang 'tags.block_tags.nav.favourite'}, 'content' => $tags_block_favourite, 'is_enabled' => !! $oUserCurrent ]
    ]}
{/block}

{* Подвал *}
{block 'block_footer'}
	{include 'components/tags/search-form.tags.tpl' mods='light'}
{/block}