{**
 * Выбор блогов для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} feed-blogs"}
{/block}

{block 'block_title'}
	{$aLang.feed.blogs.title}
{/block}

{block 'block_content'}
	{include 'components/feed/blogs.tpl' blogsJoined=$blogsJoined blogsSubscribed=$blogsSubscribed}
{/block}