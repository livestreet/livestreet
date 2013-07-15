{**
 * Пагинация
 *
 * @styles assets/css/common.css
 *}

{if $aPaging and $aPaging.iCountPage > 1}
	<nav class="pagination js-pagination" role="navigation">
		<ul class="pagination--list">
			{if $aPaging.iPrevPage}
				<li class="pagination--item">
					<a href="{$aPaging.sBaseUrl}{if $aPaging.iPrevPage > 1}/page{$aPaging.iPrevPage}{/if}/{$aPaging.sGetParams}" 
					   class="pagination--item-inner pagination--item-link js-pagination-prev" 
					   title="{$aLang.paging_previos}">&larr; {$aLang.paging_previos}</a>
				</li>
			{else}
				<li class="pagination--item pagination--prev">
					<span class="pagination--item-inner pagination--item-text">&larr; {$aLang.paging_previos}</span>
				</li>
			{/if}
			
			
			{if $aPaging.iNextPage}
				<li class="pagination--item">
					<a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}" 
					   class="pagination--item-inner pagination--item-link js-pagination-next" 
					   title="{$aLang.paging_next}">{$aLang.paging_next} &rarr;</a>
				</li>
			{else}
				<li class="pagination--item pagination--next">
					<span class="pagination--item-inner pagination--item-text">{$aLang.paging_next} &rarr;</span>
				</li>
			{/if}
		</ul>

		<ul class="pagination--list">
			{if $aPaging.iCurrentPage > 1}
				<li class="pagination--item">
					<a class="pagination--item-inner pagination--item-link" href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}" title="{$aLang.paging_first}">{$aLang.paging_first}</a>
				</li>
			{/if}
			

			{foreach $aPaging.aPagesLeft as $iPage}
				<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPaging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			
			<li class="pagination--item active"><span class="pagination--item-inner">{$aPaging.iCurrentPage}</span></li>
			
			{foreach $aPaging.aPagesRight as $iPage}
				<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPaging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			
			
			{if $aPaging.iCurrentPage < $aPaging.iCountPage}
				<li class="pagination--item">
					<a class="pagination--item-inner pagination--item-link" href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}" title="{$aLang.paging_last}">{$aLang.paging_last}</a>
				</li>
			{/if}					
		</ul>
	</nav>
{/if}