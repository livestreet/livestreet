{if $aPaging and $aPaging.iCountPage>1} 
	<div class="pagination">
		<ul>
			{if $aPaging.iCurrentPage>1}<li><a href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}">{$aLang.paging_first}</a></li>{/if}
			
			
			{if $aPaging.iPrevPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iPrevPage}/{$aPaging.sGetParams}">{$aLang.paging_previos}</a></li>
			{else}
				<li>{$aLang.paging_previos}</li>
			{/if}
			
			
			{foreach from=$aPaging.aPagesLeft item=iPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			<li class="active">{$aPaging.iCurrentPage}</li>
			{foreach from=$aPaging.aPagesRight item=iPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			
			
			{if $aPaging.iNextPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}">{$aLang.paging_next}</a></li>
			{else}
				<li>{$aLang.paging_next}</li>
			{/if}
			
			
			{if $aPaging.iCurrentPage<$aPaging.iCountPage}<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}">{$aLang.paging_last}</a></li>{/if}					
		</ul>
	</div>
{/if}