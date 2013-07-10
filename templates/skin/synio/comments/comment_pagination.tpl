{**
 * Пагинация комментариев
 *}

{if $aPagingCmt and $aPagingCmt.iCountPage>1}
	{if $aPagingCmt.sGetParams}
		{$sGetSep = '&'}
	{else}
		{$sGetSep = '?'}
	{/if}
	
	<div class="pagination pagination-comments">				
		<ul>
			<li>{$aLang.paging}:</li>				
				
			{if $oConfig->GetValue('module.comment.nested_page_reverse')}
				{if $aPagingCmt.iCurrentPage > 1}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage=1">&larr;</a></li>
				{/if}

				{foreach $aPagingCmt.aPagesLeft as $iPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}

				<li class="active">{$aPagingCmt.iCurrentPage}</li>

				{foreach $aPagingCmt.aPagesRight as $iPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}

				{if $aPagingCmt.iCurrentPage < $aPagingCmt.iCountPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iCountPage}">{$aLang.paging_last}</a></li>
				{/if}
			{else}
				{if $aPagingCmt.iCurrentPage < $aPagingCmt.iCountPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$aPagingCmt.iCountPage}">{$aLang.paging_last}</a></li>
				{/if}
				
				{foreach $aPagingCmt.aPagesRight as $iPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}

				<li class="active">{$aPagingCmt.iCurrentPage}</li>

				{foreach $aPagingCmt.aPagesLeft as $iPage}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage={$iPage}">{$iPage}</a></li>
				{/foreach}
				
				{if $aPagingCmt.iCurrentPage > 1}
					<li><a href="{$aPagingCmt.sGetParams}{$sGetSep}cmtpage=1">&rarr;</a></li>
				{/if}
			{/if}
		</ul>
	</div>
{/if}