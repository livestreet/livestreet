{**
 * Пагинация комментариев
 *
 * @styles assets/css/common.css
 *}

{if $aPagingCmt and $aPagingCmt.iCountPage>1}
	{if $aPagingCmt.sGetParams}
		{$sGetSep = '&'}
	{else}
		{$sGetSep = '?'}
	{/if}
	
	<nav class="pagination pagination-comments js-pagination" role="navigation">
		<ul class="pagination--list">
			{if $aPagingCmt.iPrevPage}
				<li class="pagination--item pagination--prev">
					<a class="pagination--item-inner pagination--item-link js-pagination-prev" 
					   href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iPrevPage}" 
					   title="{$aLang.paging_previos}">&larr; {$aLang.paging_previos}</a>
					</li>
			{else}
				<li class="pagination--item pagination--prev">
					<span class="pagination--item-inner pagination--item-text">&larr; {$aLang.paging_previos}</span>
				</li>
			{/if}
			
			
			{if $aPagingCmt.iNextPage}
				<li class="pagination--item pagination--next">
					<a class="pagination--item-inner pagination--item-link js-pagination-next" 
					   href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iNextPage}" 
					   title="{$aLang.paging_next}">{$aLang.paging_next} &rarr;</a>
				</li>
			{else}
				<li class="pagination--item pagination--next">
					<span class="pagination--item-inner pagination--item-text">{$aLang.paging_next} &rarr;</span>
				</li>
			{/if}
		</ul>

		<ul class="pagination--list">
			{if $oConfig->GetValue('module.comment.nested_page_reverse')}
				{if $aPagingCmt.iCurrentPage > 1}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage=1">{$aLang.paging_first}</a></li>
				{/if}


				{foreach $aPagingCmt.aPagesLeft as $iPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}

				<li class="pagination--item active"><span class="pagination--item-inner pagination--item-text">{$aPagingCmt.iCurrentPage}</span></li>

				{foreach $aPagingCmt.aPagesRight as $iPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}


				{if $aPagingCmt.iCurrentPage < $aPagingCmt.iCountPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iCountPage}" title="{$aLang.paging_last}">{$aLang.paging_last}</a></li>
				{/if}
			{else}
				{if $aPagingCmt.iCurrentPage < $aPagingCmt.iCountPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iCountPage}">{$aLang.paging_last}</a></li>
				{/if}
				

				{foreach $aPagingCmt.aPagesRight as $iPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}

				<li class="pagination--item active"><span class="pagination--item-inner pagination--item-text">{$aPagingCmt.iCurrentPage}</span></li>

				{foreach $aPagingCmt.aPagesLeft as $iPage}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}
				

				{if $aPagingCmt.iCurrentPage > 1}
					<li class="pagination--item"><a class="pagination--item-inner pagination--item-link" href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage=1">{$aLang.paging_first}</a></li>
				{/if}
			{/if}
		</ul>
	</nav>
{/if}