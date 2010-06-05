<div class="profiler-table">
	<table class="profiler entries">
		{foreach from=$oReport->getAllEntries() item=oEntry}
			<tr class="entry_{$oReport->getId()}_all entry_{$oReport->getId()}_{$oEntry->getName()}{if $oEntry->getChildCount()!=0} has-child{/if}">
				<td align="center" width="20px">{if $oEntry->getChildCount()!=0}<img src="{cfg name='path.root.web'}/plugins/profiler/templates/skin/developer/images/open.gif" alt="+" title="{$aLang.comment_collapse}/{$aLang.comment_expand}" class="folding lsProfiler_tree" id="tree_{$oReport->getId()}_{$oEntry->getId()}" style="margin-right:3px;"/>{/if}</td>
				<td width="5%">{$oEntry->getId()}</td>
				<td width="12%">{$oEntry->getName()}</td>
				<td width="12%" class="time">{$oEntry->getTimeFull()}</td>
				<td width="18%">{$oReport->getEntryFullShare($oEntry->getId())}% ({$oReport->getEntryShare($oEntry->getId())}%)</td>				
				<td>{$oEntry->getComment()}</td>
			</tr>
		{/foreach}
	</table>
</div>