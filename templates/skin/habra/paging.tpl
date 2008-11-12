{if $aPaging and $aPaging.iCountPage>1} 
					<div class="prev_next">    					
    					&#8592;&nbsp;
    					{if $aPaging.iPrevPage}
    						<a href="{$aPaging.sBaseUrl}/page{$aPaging.iPrevPage}/{$aPaging.sGetParams}" class="prev_next">сюда</a>
    					{else}
    						сюда
    					{/if}
    					&nbsp;&nbsp;
    					{if $aPaging.iNextPage}
    						<a href="{$aPaging.sBaseUrl}/page{$aPaging.iNextPage}/{$aPaging.sGetParams}" class="prev_next">туда</a>
    					{else}
    						туда
    					{/if}    					
    					&nbsp;&#8594;    				
    				</div>
					<div class="pages">
						{if $aPaging.iCurrentPage>1}
							<a class="nextprev" href="{$aPaging.sBaseUrl}/{$aPaging.sGetParams}">&larr;</a>
						{/if}
						{foreach from=$aPaging.aPagesLeft item=iPage}
							<a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a>
						{/foreach}
						<span class="current">{$aPaging.iCurrentPage}</span>
						{foreach from=$aPaging.aPagesRight item=iPage}
							<a href="{$aPaging.sBaseUrl}/page{$iPage}/{$aPaging.sGetParams}">{$iPage}</a>
						{/foreach}
						{if $aPaging.iCurrentPage<$aPaging.iCountPage}
							<a class="nextprev" href="{$aPaging.sBaseUrl}/page{$aPaging.iCountPage}/{$aPaging.sGetParams}">&rarr;</a>
						{/if}
					</div>
{/if}