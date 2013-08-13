{**
 * Блок со списоком блогов
 * Список блогов
 *
 * @styles css/blocks.css
 *}

<ul class="dotted-list blog-list-compact">
	{foreach $aBlogs as $oBlog}
		<li class="dotted-list-item">
			<span class="dotted-list-item-value">{$oBlog->getRating()}</span>

			{strip}
				<span class="dotted-list-item-label">
					<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
					{if $oBlog->getType()=='close'}<i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>{/if}
				</span>
			{/strip}
		</li>
	{/foreach}
</ul>				