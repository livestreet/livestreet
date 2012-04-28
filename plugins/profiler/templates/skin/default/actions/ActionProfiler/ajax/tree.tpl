{if $oReport}
	<a href="#" class="profiler tree {if $sAction=='tree'}active{/if}" onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','tree',this); return false;">{$aLang.plugin.profiler.entries_show_tree}</a> 
	<a href="#" class="profiler all {if $sAction=='all'}active{/if}" onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','all',this); return false;">{$aLang.plugin.profiler.entries_show_all} ({$oReport->getStat('count')})</a> 
	<a href="#" class="profiler query {if $sAction=='query'}active{/if}"  onclick="ls.profiler.toggleEntriesByClass('{$oReport->getId()}','query',this); return false;">{$aLang.plugin.profiler.entries_show_query} ({$oReport->getStat('query')})</a>
	
	{assign var="sTemplatePathPlugin" value=$aTemplatePathPlugin.profiler}
	{include file="$sTemplatePathPlugin/actions/ActionProfiler/ajax/level.tpl"}
{else}
	 {$aLang.error}
{/if}