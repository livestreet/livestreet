{if $oUserCurrent}
	<section class="block block-type-activity">
		<header class="block-header">
			<h3>{$aLang.userfeed_block_blogs_title}</h3>
		</header>
		
		<div class="block-content">
			<small class="note">{$aLang.userfeed_settings_note_follow_blogs}</small>

			{if count($aUserfeedBlogs)}
				<ul class="stream-settings-blogs">
					{foreach from=$aUserfeedBlogs item=oBlog}
						{assign var=iBlogId value=$oBlog->getId()}
						<li><input class="userfeedBlogCheckbox input-checkbox"
									type="checkbox"
									{if isset($aUserfeedSubscribedBlogs.$iBlogId)} checked="checked"{/if}
									onClick="if (jQuery(this).prop('checked')) { ls.userfeed.subscribe('blogs',{$iBlogId}) } else { ls.userfeed.unsubscribe('blogs',{$iBlogId}) } " />
							<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
						</li>
					{/foreach}
				</ul>
			{else}
				<small class="notice-empty">{$aLang.userfeed_no_blogs}</small>
			{/if}
		</div>
	</section>
{/if}