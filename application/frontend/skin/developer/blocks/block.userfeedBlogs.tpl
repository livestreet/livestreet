{**
 * Выбор блогов для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.feed.blogs.title}{/block}
{block 'block_type'}feed-blogs{/block}

{block 'block_content'}
	{include 'components/feed/blogs.tpl' blogsJoined=$blogsJoined blogsSubscribed=$blogsSubscribed}
{/block}