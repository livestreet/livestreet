	{if $aPaging and $aPaging.iCountPage>1} 
			<div id="pagination">
				<p>
					&larr;				
					{if $aPaging.iPrevPage}
    					<a href="{$aPaging.sBaseUrl}/page{$aPaging.iPrevPage}/{$aPaging.sGetParams}">предыдущая</a>
    				{else}
    					предыдущая
    				{/if}
    				&nbsp; &nbsp;
    				{if $aPaging.iNextPage}
    					<a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}">следующая</a>
    				{else}
    					следующая
    				{/if}
					&rarr;
				</p>
				<ul>
					<li>Страницы:</li>				
					
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
						<li><a href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}">последняя</a></li>
					{/if}					
				</ul>
			</div>
	{/if}