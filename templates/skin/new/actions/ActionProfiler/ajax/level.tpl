	<div class="profiler-table">
		<table class="profiler entries">
			{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr class="entry_{$oReport->getId()}_all entry_{$oReport->getId()}_{$oEntry->getName()}{if $oEntry->getChildCount()!=0} child{/if}">
				<td>{if $oEntry->getChildCount()!=0}<img src="{cfg name='path.static.skin'}/images/open.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding lsProfiler_tree" id="tree_{$oReport->getId()}_{$oEntry->getId()}" style="margin-right:3px;"/>{/if}</td>
				<td width="6%">{$oEntry->getId()}</td>
				<td width="15%">{$oEntry->getName()}</td>
				<td width="12%">{$oEntry->getTimeFull()}</td>
				<td>{$oEntry->getComment()}</td>
			</tr>
			{/foreach}
		</table>
	</div>