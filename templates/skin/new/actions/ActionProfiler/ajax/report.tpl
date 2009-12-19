{if $oReport}
	<a href="#" class="profiler all active" onclick="lsProfiler.toggleEntriesByClass('{$oReport->getId()}','all',true); return false;">{$aLang.profiler_entries_show_all} ({$oReport->getCountEntriesByName()})</a> 
	<a href="#" class="profiler query"  onclick="lsProfiler.toggleEntriesByClass('{$oReport->getId()}','query',true); return false;">{$aLang.profiler_entries_show_query} ({$oReport->getCountEntriesByName('query')})</a>
	<div class="topic people top-blogs talk-table">
		<table class="profiler entries">
			{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr class="entry_{$oReport->getId()}_all entry_{$oReport->getId()}_{$oEntry->getName()}">
				<td width="8%">{$oEntry->getId()}</td>
				<td width="18%">{$oEntry->getName()}</td>
				<td width="15%">{$oEntry->getTimeFull()}</td>
				<td>{$oEntry->getComment()}</td>
			</tr>
			{/foreach}
		</table>
	</div>
{else}
	 {$aLang.error}
{/if}