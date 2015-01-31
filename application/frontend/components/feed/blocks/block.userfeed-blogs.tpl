{**
 * Выбор блогов для чтения в ленте
 *}

{extends 'Component@block.block'}

{block 'block_options' append}
	{$mods = "{$mods} feed-blogs"}
{/block}

{block 'block_title'}
	{$aLang.feed.blogs.title}
{/block}

{block 'block_content'}
	{include '../blogs.tpl' blogsJoined=$blogsJoined blogsSubscribed=$blogsSubscribed}
{/block}