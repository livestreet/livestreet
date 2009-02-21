{include file='header.tpl' showWhiteBack=true}

			<h1>Результаты поиска: <span>{$aReq.q|escape:'html'}</span></h1>
			<ul class="block-nav">
			{foreach from=$aRes.aCounts item=iCount key=sType name="sTypes"}
				<li {if $aReq.sType == $sType}class="active"{/if}>					
					{if $smarty.foreach.sTypes.first}<strong></strong>{/if}
					<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/{$sType}/?q={$aReq.q|escape:'html'}">
						{$iCount} 
						{if $sType=="topics"}
							топиков
						{elseif $sType=="comments"}
							комментариев
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
				<h2>Удивительно, но поиск не дал результатов</h2> 
			{/if}

{include file='footer.tpl'}