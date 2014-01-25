{**
 * Блок со списоком блогов
 * Список блогов
 *
 * @styles css/blocks.css
 *}

<ul class="item-list">
	{foreach $aBlogs as $oBlog}
		<li>
			<a href="{$oBlog->getUrlFull()}"><img src="{$oBlog->getAvatarPath(48)}" alt="{$oBlog->getTitle()|escape}" class="avatar" /></a>
			
			{if $oBlog->getType() == 'close'}<i title="{$aLang.blog.private}" class="icon icon-lock"></i>{/if}
			<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>
			
			<p>{$aLang.vote.rating}: <strong>{$oBlog->getRating()}</strong></p>
		</li>
	{/foreach}
</ul>				