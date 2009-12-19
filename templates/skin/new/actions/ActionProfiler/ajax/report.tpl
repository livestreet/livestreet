{if $oReport}
	<a href="#" class="profiler all active" onclick="lsProfiler.toggleEntriesByClass('{$oReport->getId()}','all',true); return false;">{$aLang.profiler_entries_show_all} ({$oReport->getCountEntriesByName()})</a> 
	<a href="#" class="profiler query"  onclick="lsProfiler.toggleEntriesByClass('{$oReport->getId()}','query',true); return false;">{$aLang.profiler_entries_show_query} ({$oReport->getCountEntriesByName('query')})</a>
	<div class="topic people top-blogs talk-table">
		<table class="profiler entries">
			{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr class="entry_{$oReport->getId()}_all entry_{$oReport->getId()}_{$oEntry->getName()}">
				<td width="6%">{$oEntry->getId()}</td>
				<td width="15%">{$oEntry->getName()}</td>
				<td width="12%">{$oEntry->getTimeFull()}</td>
				{assign var=sId value=$oEntry->getId()}
				<td width="12%">{$oReport->getEntryShare($sId)}%</td>
				<td>{$oEntry->getComment()}</td>
			</tr>
			{/foreach}
		</table>
	</div>
{else}
	 {$aLang.error}
{/if}