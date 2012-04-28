{if $oReport}
	<a href="#" class="profiler tree {if $sAction=='tree'}active{/if}" onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','tree',this); return false;">{$aLang.plugin.profiler.entries_show_tree}</a> 
	<a href="#" class="profiler all {if $sAction=='all'}active{/if}" onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','all',this); return false;">{$aLang.plugin.profiler.entries_show_all} ({$oReport->getStat('count')})</a> 
	<a href="#" class="profiler query {if $sAction=='query'}active{/if}"  onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','query',this); return false;">{$aLang.plugin.profiler.entries_show_query} ({$oReport->getStat('query')})</a>
	
	<div class="profiler-table">
		<table class="profiler entries">
			{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr class="entry_{$oReport->getId()}_all entry_{$oReport->getId()}_{$oEntry->getName()}{if $oEntry->getChildCount()!=0} child{/if}">
				<td></td>
				<td width="5%">{$oEntry->getId()}</td>
				<td width="12%">{$oEntry->getName()}</td>
				<td width="12%" class="time">{$oEntry->getTimeFull()}</td>
				<td width="18%">{$oReport->getEntryFullShare($oEntry->getId())}% ({$oReport->getEntryShare($oEntry->getId())}%)</td>
				<td>{$oEntry->getComment()}</td>
			</tr>
			{/foreach}
		</table>
	</div>
{else}
	 {$aLang.error}
{/if}