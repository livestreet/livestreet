{include file='header.tpl' showWhiteBack=true}

			<h1>{$aLang.search_results}: <span>{$aReq.q|escape:'html'}</span></h1>
			<ul class="block-nav">
			{foreach from=$aRes.aCounts item=iCount key=sType name="sTypes"}
				<li {if $aReq.sType == $sType}class="active"{/if}>					
					{if $smarty.foreach.sTypes.first}<strong></strong>{/if}
					<a href="{router page='search'}{$sType}/?q={$aReq.q|escape:'html'}">
						{$iCount} 
						{if $sType=="topics"}
							{$aLang.search_results_count_topics}
						{elseif $sType=="comments"}
							{$aLang.search_results_count_comments}
						{/if}
					</a>
					{if $smarty.foreach.sTypes.last}<em></em>{/if}
				</li>				
			{/foreach}
			</ul>
			<br />

			{if $bIsResults}
				{if $aReq.sType == 'topics'}
					{include file='topic_list.tpl'}
				{elseif $aReq.sType == 'comments'}
					{include file='comment_list.tpl'}
				{/if}
			{else}
				<h2>{$aLang.search_results_empty}</h2> 
			{/if}

{include file='footer.tpl'}