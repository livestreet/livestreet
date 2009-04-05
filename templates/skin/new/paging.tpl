	{if $aPaging and $aPaging.iCountPage>1} 
			<div id="pagination">
				<p>
					&larr;				
					{if $aPaging.iPrevPage}
    					<a href="{$aPaging.sBaseUrl}/page{$aPaging.iPrevPage}/{$aPaging.sGetParams}">{$aLang.paging_previos}</a>
    				{else}
    					{$aLang.paging_previos}
    				{/if}
    				&nbsp; &nbsp;
    				{if $aPaging.iNextPage}
    					<a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}">{$aLang.paging_next}</a>
    				{else}
    					{$aLang.paging_next}
    				{/if}
					&rarr;
				</p>
				<ul>
					<li>{$aLang.paging}:</li>				
					
					{if $aPaging.iCurrentPage>1}
						<li><a href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}">&larr;</a></li>
					{/if}
					{foreach from=$aPaging.aPagesLeft item=iPage}
						<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
					{/foreach}
					<li class="active">{$aPaging.iCurrentPage}</li>
					{foreach from=$aPaging.aPagesRight item=iPage}
						<li><a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a></li>
					{/foreach}
					{if $aPaging.iCurrentPage<$aPaging.iCountPage}
						<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}">{$aLang.paging_last}</a></li>
					{/if}					
				</ul>
			</div>
	{/if}