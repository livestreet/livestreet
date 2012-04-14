{if $aPaging and $aPaging.iCountPage>1} 
	<div class="pagination">
		<ul>
			{if $aPaging.iCurrentPage>1}<li><a href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}" title="{$aLang.paging_first}"><i class="icon-step-backward"></i></a></li>{/if}
			
			
			{if $aPaging.iPrevPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iPrevPage}/{$aPaging.sGetParams}" class="js-paging-prev-page" title="{$aLang.paging_previos}"><i class="icon-chevron-left"></i></a></li>
			{/if}
			
			
			{foreach from=$aPaging.aPagesLeft item=iPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			
			<li class="active"><span>{$aPaging.iCurrentPage}</span></li>
			
			{foreach from=$aPaging.aPagesRight item=iPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
			{/foreach}
			
			
			{if $aPaging.iNextPage}
				<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}" class="js-paging-next-page" title="{$aLang.paging_next}"><i class="icon-chevron-right"></i></a></li>
			{/if}
			
			
			{if $aPaging.iCurrentPage<$aPaging.iCountPage}<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}" title="{$aLang.paging_last}"><i class="icon-step-forward"></i></a></li>{/if}					
		</ul>
	</div>
{/if}