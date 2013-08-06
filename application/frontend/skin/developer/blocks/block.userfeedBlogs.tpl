{**
 * Выбор блогов для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.userfeed_block_blogs_title}{/block}
{block name='block_type'}activity{/block}

{block name='block_content'}
	<small class="note">{$aLang.userfeed_settings_note_follow_blogs}</small>

	{if count($aUserfeedBlogs)}
		<ul class="stream-settings-blogs">
			{foreach $aUserfeedBlogs as $oBlog}
				{$iBlogId = $oBlog->getId()}
				<li><input class="userfeedBlogCheckbox input-checkbox"
							type="checkbox"
							{if isset($aUserfeedSubscribedBlogs.$iBlogId)} checked{/if}
							onClick="if (jQuery(this).prop('checked')) { ls.userfeed.subscribe('blogs',{$iBlogId}) } else { ls.userfeed.unsubscribe('blogs',{$iBlogId}) } " />
					<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
				</li>
			{/foreach}
		</ul>
	{else}
		<small class="notice-empty">{$aLang.userfeed_no_blogs}</small>
	{/if}
{/block}